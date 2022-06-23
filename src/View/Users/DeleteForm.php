<?php

namespace App\View\Users;

class DeleteForm extends \App\View\Main
{
    public function content(array $data)
    {
        $isNew = ! isset($data['data']['id']);
        ?>

            <div class="row">
                <div class="col-lg-12">
                    <div class="block">
                        <!--<div class="block-header">
                            <ul class="block-options">
                                <li>
                                    <button type="button"><i class="si si-settings"></i></button>
                                </li> 
                            </ul>
                            <h3 class="block-title">Static Labels</h3> 
                        </div>-->
                        <div class="block-content block-content-narrow">
                            <form class="form-horizontal push-10-t" action="<?= $data['url']['approve'] ?>" method="post">
                                <input type="hidden" name="id" value="<?= $data['user']['id'] ?>" >
                                <div class="form-group">
                                    <div class="col-sm-9">
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h3 class="font-w300 push-15"><?= $data['title'] ?></h3>
                                            <p>Are you sure, you want to delete this user <a class="alert-link" href="javascript:void(0)"><?= $data['user']['name'] ?></a>!?</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-9">
                                        <button class="btn btn-sm btn-primary" type="submit">Delete</button>
                                        <a class="btn btn-sm btn-default" href="<?= $data['url']['cancel'] ?>">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        <?php
    }
}   