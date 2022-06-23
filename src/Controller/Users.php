<?php

    namespace App\Controller;

    class Users
    {
        public function run(){

            $pdo = \App\Service\DB::get();

            $stms = $pdo->prepare("
            
                SELECT
                    *
                FROM
                    `users`
            
            ");
            $stms->execute();

            $view = new \App\View\Users();
            $view->render([
                'title' => 'Users',
                'data' => $stms->fetchAll(),
            ]); 
        }

        public function runAdd(){

            $validator = $this->getValidator();

            if($_POST && $validator->check($_POST)){


                $pdo = \App\Service\DB::get();
                $stmt = $pdo->prepare("
                    INSERT INTO
                        `users`(
                            `email`,
                            `password`,
                            `name`,
                            `privilege`
                        ) VALUES (
                            :email,
                            :password,
                            :name,
                            :privilege
                        )
                ");
                $stmt->execute([
                    ':email' => $_POST['email'],
                    ':password' => sha1($_POST['password']),
                    ':name' => $_POST['name'],
                    ':privilege' => $_POST['privilege'],
                ]);
                
                header('Location: /');
            }


            $view = new \App\View\Users\Form();
            $view->render([
                'title' => 'Create a new user!',
                'data' => $_POST,
                'messages' => $validator->getMessages(),
            ]);
        }


        public function runUpdate(){

            if(! isset($_GET['id'])){
                header('Location: /');
                return;
            }

            $pdo = \App\Service\DB::get();

            $stmt = $pdo->prepare("

                SELECT
                    *
                FROM
                    `users`
                WHERE
                    `id` = :uid
            
            ");

            $stmt->execute([
                ':uid' => $_GET['id']
            ]);

            if( ! $user = $stmt->fetch()){
                
                header('Location: /');
                
                return;
            }

            $validator = $this->getValidator(true);
            if($_POST && $validator->check($_POST)){
                if($_POST['password'] == ''){
                    $stmt = $pdo->prepare("
                        UPDATE
                            `users`
                        SET
                            `email` = :email,
                            `name` = :name,
                            `privilege` = :privilege
                        WHERE
                            `id` = :id    
                            
                    ");
                    $stmt->execute([
                        ':email' => $_POST['email'],
                        ':name' => $_POST['name'],
                        ':privilege' => $_POST['privilege'],
                        ':id' => $_GET['id'],
                    ]);
                } else {
                    $stmt = $pdo->prepare("
                        UPDATE
                            `users`
                        SET
                            `email` = :email,
                            `name` = :name,
                            `password` = :password,
                            `privilege` = :privilege
                        WHERE
                            `id` = :id    
                            
                    ");
                    $stmt->execute([
                        ':email' => $_POST['email'],
                        ':name' => $_POST['name'],
                        ':password' => sha1($_POST['password']),
                        ':privilege' => $_POST['privilege'],
                        ':id' => $_GET['id'],
                    ]);

                }
                header('Location: /users');
                return;
            }


            $view = new \App\View\Users\Form();
            $view->render([
                'title' => 'User editing',
                'data' => $user,
                'messages' => $validator->getMessages(),
            ]);

        }


        public function runDelete(){

            $pdo = \App\Service\DB::get();

            if(isset($_POST['id'])){
                $stmt = $pdo->prepare("DELETE FROM `users` WHERE `id` = :uid"); 
                $stmt->execute([
                    ':uid' => $_POST['id'],
                ]);
                header('Location: /users');
                return;
            }

            if(! isset($_GET['id'])){
                header('Location: /users');
                return;
            }


            $stmt = $pdo->prepare("
                SELECT
                    *
                FROM
                    `users`
                WHERE
                    `id` = :uid
            
            ");

            $stmt->execute([
                ':uid' => $_GET['id'],
            ]);
            
            if( ! $user = $stmt->fetch()){
                header('Location: /users');
                return;
            }

            

            $view = new \App\View\Users\DeleteForm();


            $view->render([
                'title' => 'Delete user',
                'user' => $user,
                'url' =>   [
                    'approve' => '/users/delete',
                    'cancel' => '/users',
                ]
             ]);
        }

        private function getValidator($isIpdate = false){

            $validator = new \App\Service\Validator();
            $validator
                ->setRules('email',function($value){
                    return ! is_null($value) && mb_strlen($value) > 0;
                },  'Fill this field!')
                ->setRules('email',function($value){
                    return preg_match('/[^@]+@[^@]+/',$value);
                },  'Wrong email!')
                ->setRules('name',function($value){
                    return preg_match('/.{3,50}/',$value);
                },  'Incorrect name!')
                ->setRules('prvilege',function($value){
                    return in_array((int) $value, [0,1]);  
                },  'The privilege is incorrect')
                ->setRules('confirm-password',function($value,$data){
                    return isset($data['password']) && $data['password'] === $value; 
                },  'Wrong confirmation password!');

            if($isIpdate){
                $validator
                    ->setRules('confirm-password',function($value,$data){
                        return $value == '' || isset($data['password']) && $data['password'] === $value; 
                    },  'Wrong confirmation password!');

            } else {
                $validator
                ->setRules('password',function($value){
                    return preg_match('/.{8,100}/',$value);
                },  'The password has to contain at least 8 symbols');
            }

            return $validator;
        }

        
    }

?>