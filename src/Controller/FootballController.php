<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Mailer\Email;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Football Controller
 *
 *
 * @method \App\Model\Entity\Football[] paginate($object = null, array $settings = [])
 */
class FootballController extends AppController {

    private $system_email = 'psj.footballclub@gmail.com';

    public function index() {

        $games = $this->Game->get_next_games(null);

        foreach ($games as $key => $game) {
            $players = $this->Participate->get_players($game['id']);
            $games[$key]['hasresponded'] = false;
            $games[$key]['isParticipating'] = false;
            $games[$key]['ableToConfirm'] = false;
            $games[$key]['number'] = $this->Participate->get_number_players($game['id']);
            $game['date']->modify('+2 hours');
            if ($game['date']->isWithinNext('6 days')) {
                $games[$key]['ableToConfirm'] = true;
            }
            foreach ($players as $seckey => $player) {
                $players[$seckey]['fullname'] = null;
                $players[$seckey]['isParticipating'] = null;
                if ($this->Participate->already_participate($players[$seckey]['id_u'], $game['id'])) {
                    if ($players[$seckey]['id_u'] == $this->Auth->user('id')) {
                        $games[$key]['isParticipating'] = true;
                    }
                    $players[$seckey]['isParticipating'] = true;
                }
                if ($players[$seckey]['id_u'] == $this->Auth->user('id')) {
                    $games[$key]['hasresponded'] = true;
                }
                $players[$seckey]['fullname'] = $this->Users->get_fullname($players[$seckey]['id_u']);
            }
            $games[$key]['players'] = $players;
        }
        $this->set('games', $games);
    }

    public function membre() {
        if ($this->Auth->user('role') != 'membre') {
            $this->Flash->error(__('Access non autorisée'));
            return $this->redirect(['controller' => 'Football', 'action' => 'index']);
        }
        if ($this->request->is('post')) {
            $resultatForm = $this->request->data;
            $this->uploadimg($resultatForm);
        }
        $number = $this->Participate->get_number_matchs($this->Auth->user('id'));
        $this->set('number', $number);
    }

    public function admin() {

        if ($this->Auth->user('role') != 'admin') {
            $this->Flash->error(__('Access non autorisée'));
            return $this->redirect(['controller' => 'Football', 'action' => 'index']);
        }
        if ($this->request->is('post')) {
            $resultatForm = $this->request->data;
            $this->uploadimg($resultatForm);
        }
        $invitations = $this->Participate->get_invite();
        foreach ($invitations as $key => $invitation) {
            $invitations[$key]['invitedBy'] = $this->Users->get_fullname($invitation['id_u']);
            $invitations[$key]['gameDate'] = $this->Game->get_date($invitation['id_g']);
        }
        $number = $this->Participate->get_number_matchs($this->Auth->user('id'));
        $this->set('number', $number);
        $this->set('invitations', $invitations);
    }

    public function user($user_id = null) {
        if (!$user_id) {
            $this->Flash->error(__('Access non autorisée'));
            return $this->redirect(['controller' => 'Football', 'action' => 'index']);
        }
        $info = $this->Users->get_info($user_id);
        $number = $this->Participate->get_number_matchs($user_id);
        $this->set('info', $info[0]);
        $this->set('number', $number);
    }

    public function creategame() {
        if ($this->Auth->user('role') != 'admin') {
            $this->Flash->error(__('Access non autorisée'));
            return $this->redirect(['controller' => 'Football', 'action' => 'index']);
        }
        $lastdate = $this->Game->get_last_date();
        $now = Time::now();
        $now->setDate($lastdate->year, $lastdate->month, $lastdate->day);
        $now->addDays(7);
        $this->Game->add_game($now);
        $deleted_games = $this->Game->get_futur_deleted_games();
        foreach ($deleted_games as $deleted_game) {
            $this->Participate->delete_by_game_id($deleted_game['id']);
        }
        $this->Game->delete_game();
        return $this->redirect(['controller' => 'Football', 'action' => 'admin']);
    }

    private function sendemail($user_id = null, $subject = null, $game_id = null, $template = null, $data = null) {
        if ($this->Auth->user('role') != 'admin') {
            return $this->redirect(['controller' => 'Football', 'action' => 'index']);
            $this->Flash->error(__('Access non autorisée'));
        }
        if ($game_id) {
            $gamedate = $this->Game->get_date($game_id)->nice('Europe/Paris', 'fr-FR');
        } else {
            $gamedate = null;
        }
        $fullname = $this->Users->get_fullname($user_id);
        $toEmail = $this->Users->get_email($user_id);
        $this->Flash->success($subject . ' envoyé à ' . $fullname . ' à l\'adresse ' . $toEmail);
        $email = new Email('default');
        $email->template($template)
                ->viewVars(array('gamedate' => $gamedate, 'fullname' => $fullname, 'data' => $data))
                ->emailFormat('html')
                ->from([$this->system_email => 'PSJ'])
                ->to($toEmail)
                ->subject($subject)
                ->send();
    }

    public function participate($user_id = null, $game_id = null) {

        if (!$this->Participate->already_respond($user_id, $game_id)) {
            $this->Participate->participate($user_id, $game_id);
            $subject = 'Participation au match du ' . $this->Game->get_date($game_id)->nice('Europe/Paris', 'fr-FR');
            $this->sendemail($user_id, $subject, $game_id, 'participate');
        } else {
            if (!$this->Participate->already_participate($user_id, $game_id)) {
                $this->Participate->reparticipate($user_id, $game_id);
                $subject = 'Participation au match du ' . $this->Game->get_date($game_id)->nice('Europe/Paris', 'fr-FR');
            }
        }
        $this->redirect(['action' => 'index']);
    }

    public function notparticipate($user_id = null, $game_id = null) {
        $this->loadModel('Participate');
        if (!$this->Participate->already_respond($user_id, $game_id)) {
            $this->Participate->notparticipate($user_id, $game_id);
        } else {
            if ($this->Participate->already_participate($user_id, $game_id)) {
                $this->Participate->unparticipate($user_id, $game_id);
            }
        }
        $this->Participate->removeinvite($user_id, $game_id);
        $this->redirect(['action' => 'index']);
    }

    public function invite() {
        if (!$this->request->data['Prenom'] || !$this->request->data['Nom'] || !$this->request->data['user_id'] || !$this->request->data['game_id']) {
            return $this->redirect(['action' => 'index']);
        }
        $user_id = $this->request->data['user_id'];
        $game_id = $this->request->data['game_id'];

        if ($this->request->is('post')) {
            if ($this->Auth->user('role') == 'admin') {
                $isAccepted = 1;
            } else {
                $isAccepted = 0;
            }
            $fullname = $this->request->data['Prenom'] . ' ' . $this->request->data['Nom'];
            if ($this->Participate->already_participate($user_id, $game_id)) {
                $this->Participate->invite($user_id, $game_id, $fullname, $isAccepted);
                if (!$isAccepted) {
                    $admins = $this->Users->get_admins();
                    pr($admins);
                    $subject = 'Demande d\'invitation de ' . $this->Users->get_fullname($user_id) . ' pour le match du ' . $this->Game->get_date($game_id)->nice('Europe/Paris', 'fr-FR');
                    foreach ($admins as $admin) {
                        $this->sendemail($admin['id'], $subject, $game_id, 'invite', $this->Users->get_fullname($user_id));
                    }
                }
            }
        }
        $this->redirect(['action' => 'index']);
    }

    public function reminduser($game_id = 14) {
        if ($this->Auth->user('role') != 'admin') {
            $this->Flash->error(__('Access non autorisée'));
            return $this->redirect(['controller' => 'Football', 'action' => 'index']);
        }
        $players = $this->Participate->get_players($game_id);

        $subject = 'Rappel pour le match du ' . $this->Game->get_date($game_id)->nice('Europe/Paris', 'fr-FR');
        foreach ($players as $key => $player) {
            $players[$key] = $player['id_u'];
        }
        $notplayers = $this->Users->not_in($players);


        foreach ($notplayers as $notplayer) {
            $this->sendemail($notplayer['id'], $subject, $game_id, 'remind');
        }
        $this->redirect(['action' => 'index']);
    }

    public function changepassword() {
        $user = $this->Users->get($this->Auth->user('id'));
        if (!empty($this->request->data)) {
            $user = $this->Users->patchEntity($user, [
                'old_password' => $this->request->data['old_password'],
                'password' => $this->request->data['password1'],
                'password1' => $this->request->data['password1'],
                'password2' => $this->request->data['password2']
                    ], ['validate' => 'password']
            );
            if ($this->Users->save($user)) {
                $this->Flash->success('The password is successfully changed');
                $this->redirect(['controller' => 'Football', 'action' => 'index']);
            } else {
                $this->Flash->error('There was an error during the save!');
            }
        }
        $this->set('user', $user);
    }

    private function uploadimg($resultatForm = null) {
        if (!empty($resultatForm)) {
            //on s'assure que l'utilisateur ait rentré le nom et la photo
            if (!empty($resultatForm['avatar'])) {
                //On crée un nouveau combattant dans la B
                //On charge l'avatar dans le fichier
                $avatar = $resultatForm['avatar'];
                //On récupère l'extention du fichier chargé
                $extensionAvatar = substr(strtolower(strrchr($avatar['name'], '.')), 1);
                //On fixe les extentions autorisées
                $auth = array('gif', 'png', 'jpg', 'jpeg');
                //
                $newAvatar = $this->Auth->user('id');
                //On vérifie que la fichié chargé est bien une photo
                if (in_array($extensionAvatar, $auth)) {
                    //on enregistre le fichier dans Webroot/img
                    $dossier = WWW_ROOT . 'img/';
                    $fichier = $newAvatar . '.png';
                    move_uploaded_file($avatar['tmp_name'], $dossier . $fichier);
                }
            }
        }
    }

    public function admininvite($response = null, $id = null) {
        if ($this->Auth->user('role') != 'admin') {
            $this->Flash->error(__('Access non autorisée'));
            return $this->redirect(['controller' => 'Football', 'action' => 'index']);
        }
        if ($response == true) {
            $this->Participate->acceptinvite($id);
        } elseif ($response == 0) {
            $this->Participate->declineinvite($id);
        }
        return $this->redirect(['action' => 'admin']);
    }

    public function confirm($game_id = null) {
        if ($this->Auth->user('role') != 'admin') {
            $this->Flash->error(__('Access non autorisée'));
            return $this->redirect(['controller' => 'Football', 'action' => 'index']);
        }
        $players = $this->Participate->get_players_not_invited($game_id);
        $subject = 'Confirmation pour le match du ' . $this->Game->get_date($game_id)->nice('Europe/Paris', 'fr-FR');
        $template = 'confirm';

        foreach ($players as $key => $player) {
            $players[$key]['name'] = $this->Users->get_fullname($player['id_u']);
            $players[$key]['guest'] = $this->Participate->get_guest($player['id_u'], $game_id);
        }
        foreach ($players as $player):
            $this->sendemail($player['id_u'], $subject, $game_id, $template, $players);
        endforeach;
        return $this->redirect(['action' => 'index']);
    }

    public function addmember() {
        if ($this->Auth->user('role') != 'admin') {
            $this->Flash->error(__('Access non autorisée'));
            return $this->redirect(['controller' => 'Football', 'action' => 'membre']);
        }

        if (!empty($this->request->data)) {
            if ($this->request->data['role']) {
                $role = 'admin';
            } else {
                $role = 'membre';
            }
            $passwords = $this->createnewpassword();
            $this->Users->add_user($this->request->data['prenom'], $this->request->data['nom'], $passwords['newhashpassword'], $this->request->data['email'], $role);
            $user_id = $this->Users->getLastId();
            $subject = 'Bienvenue ' . $this->request->data['prenom'] . ' ' . $this->request->data['nom'];
            $this->sendemail($user_id, $subject, null, 'addmember', $passwords['newpassword']);
            return $this->redirect(['action' => 'admin']);
        }
    }

    private function createnewpassword() {
        $size = mt_rand(5, 8);
        $newpassword = null;
        $characters = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
        for ($i = 0; $i < $size; $i++) {
            $newpassword .= ($i % 2) ? strtoupper($characters[array_rand($characters)]) : $characters[array_rand($characters)];
        }
        $newhashpassword = (new DefaultPasswordHasher)->hash($newpassword);
        $passwords = array('newpassword' => $newpassword, 'newhashpassword' => $newhashpassword);
        return $passwords;
    }

    public function adminsendemail() {
        if ($this->Auth->user('role') != 'admin') {
            $this->Flash->error(__('Access non autorisée'));
            return $this->redirect(['controller' => 'Football', 'action' => 'membre']);
        }
        if (!empty($this->request->data)) {
            if ($this->request->data['to']) {
                $data = array('subject' => $this->request->data['subject'], 'content' => $this->request->data['content']);
                $this->sendemail($this->request->data['to'], $data['subject'], null, 'baseemail', $data);
            } else {
                $users_email = $this->Users->find('list', ['limit' => 200])->toArray();
                $data = array('subject' => $this->request->data['subject'], 'content' => $this->request->data['content']);
                foreach ($users_email as $user_email) {
                    $this->sendemail($user_email, $data['subject'], null, 'baseemail', $data);
                }
            }
            return $this->redirect(['action' => 'admin']);
        }
        $users = $this->Users->find('list', ['limit' => 200])->toArray();
        foreach ($users as $key => $user) {
            $users[$key] = $this->Users->get_fullname($user);
        }
        $users[0] = 'Tout le monde';
        $this->set('users', $users);
    }

}
