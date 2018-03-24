<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize() {
        parent::initialize();
        $this->loadModel('Game');
        $this->loadModel('Participate');
        $this->loadModel('Users');
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        $this->loadComponent('Auth', [
            /* 'authenticate' => [
              'Form' => [
              'userModel' => 'Players',
              'fields' => [
              'username' => 'email',
              'password' => 'password'
              ]
              ],
              ], */
            'authError' => 'Access non autorisée',
            'authenticate' => [
                'Form' => [
                    'fields' => ['username' => 'email',
                                  'password' => 'password']
                ]
            ],
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'login'
            ],
            'loginRedirect' => [
                'controller' => 'Football',
                'action' => 'index'
            ],
            'logoutRedirect' => [
                'controller' => 'Users',
                'action' => 'login',
            ],
            // Si pas autorisé, on renvoit sur la page précédente
            'unauthorizedRedirect' => ['controller' => 'Users',
                'action' => 'login',]
        ]);

        // Pass settings in
//        $this->Auth->setConfig('authenticate', [
//            'Basic' => ['userModel' => 'Players'],
//            'Form' => ['userModel' => 'Players']
//        ]);

        /*
         * Enable the following components for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
    }

    public function beforeRender(Event $event) {
        // Note: These defaults are just to get started quickly with development
        // and should not be used in production. You should instead set "_serialize"
        // in each action as required.
        if (!array_key_exists('_serialize', $this->viewVars) &&
                in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
        $this->loadModel('Participate');
        $this->set('nb_invite', $this->Participate->get_nb_invite());
        $this->set('Auth', $this->Auth);
    }
    public $helpers = [
        'Form' => [
            'className' => 'Bootstrap.Form'
        ],
        'Html' => [
            'className' => 'Bootstrap.Html'
        ],
        'Modal' => [
            'className' => 'Bootstrap.Modal'
        ],
        'Navbar' => [
            'className' => 'Bootstrap.Navbar'
        ],
        'Paginator' => [
            'className' => 'Bootstrap.Paginator'
        ],
        'Panel' => [
            'className' => 'Bootstrap.Panel'
        ]
    ];
}
