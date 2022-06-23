<?php

    namespace App\Controller;

    class Accounts
    {
        public function run(){

            $pdo = \App\Service\DB::get();

            $stms = $pdo->prepare("
            
                SELECT
                    *
                FROM
                    `accounts`
            
            ");
            $stms->execute();

            $view = new \App\View\Accounts();
            $view->render([
                'title' => 'Instagram\'s accounts',
                'data' => $stms->fetchAll(),
            ]); 
        }

        public function runAdd(){

            $validator = $this->getValidator();

            if($_POST && $validator->check($_POST)){


                $pdo = \App\Service\DB::get();
                $stmt = $pdo->prepare("
                    INSERT INTO
                        `accounts`(
                            `login`,
                            `password`,
                            `id_user`
                        ) VALUES (
                            :login,
                            :password,
                            :id_user
                        )
                ");
                $stmt->execute([
                    ':login' => $_POST['login'],
                    ':password' => $_POST['password'],
                    ':id_user' => $_SESSION['auth']['id'],
                ]);
                
                header('Location: /accounts');
                return;
            }



            $view = new \App\View\Accounts\Form();
            $view->render([
                'title' => 'Create a new account!',
                'data' => $_POST,
                'messages' => $validator->getMessages(),
            ]);
        }


        public function runUpdate(){

            if(! isset($_GET['id'])){
                header('Location: /accounts');
                return;
            }

            $pdo = \App\Service\DB::get();

            $stmt = $pdo->prepare("
                SELECT
                    *
                FROM
                    `accounts`
                WHERE
                    `id` = :uid AND `id_user` =:id_user
            
            ");

            $stmt->execute([
                ':uid' => $_GET['id'],
                ':id_user' => $_SESSION['auth']['id'],
            ]);

            if( ! $account = $stmt->fetch()){
                
                header('Location: /accounts');
                
                return;
            }

            $validator = $this->getValidator(true);
            if($_POST && $validator->check($_POST)){
                $stmt = $pdo->prepare("
                    UPDATE
                        `accounts`
                    SET
                        `login` = :login,
                        `password` = :password
                    WHERE
                        `id` = :id AND `id_user` = :id_user
                        
                ");
                $stmt->execute([
                    ':login' => $_POST['login'],
                    ':password' => $_POST['password'],
                    ':id' => $_GET['id'],
                    ':id_user' => $_SESSION['auth']['id'],

                ]);

                header('Location: /accounts');
                return;
            }


            $view = new \App\View\Accounts\Form();
            $view->render([
                'title' => 'Instagram account editing',
                'data' => $account,
                'messages' => $validator->getMessages(),
            ]);

        }


        public function runDelete(){

            $pdo = \App\Service\DB::get();

            if(isset($_POST['id'])){
                $stmt = $pdo->prepare("DELETE FROM `accounts` WHERE `id` = :uid AND `id_user` = :id_user"); 
                $stmt->execute([
                    ':uid' => $_POST['id'],
                    ':id_user' => $_SESSION['auth']['id'],

                ]);
                header('Location: /accounts');
                return;
            }

            if(! isset($_GET['id'])){
                header('Location: /accounts');
                return;
            }


            $stmt = $pdo->prepare("
                SELECT
                    *
                FROM
                    `accounts`
                WHERE
                    `id` = :uid AND `id_user` = :id_user
            
            ");

            $stmt->execute([
                ':uid' => $_GET['id'],
                ':id_user' => $_SESSION['auth']['id'],
            ]);
            
            if( ! $account = $stmt->fetch()){
                header('Location: /accounts');
                return;
            }

            

            $view = new \App\View\Accounts\DeleteForm();


            $view->render([
                'title' => 'Delete inst account',
                'account' => $account,
                'url' =>   [
                    'approve' => '/accounts/delete',
                    'cancel' => '/accounts',
                ]
             ]);
        }

        private function getValidator($isIpdate = false){

            $validator = new \App\Service\Validator();
            $validator
                ->setRules('login',function($value){
                    return ! is_null($value) && mb_strlen($value) > 0;
                },  'Fill this field!')
                ->setRules('password',function($value){
                    return ! is_null($value) && mb_strlen($value) > 0;
                },  'Fill this field!');


            return $validator;
        }

        
    }

?>