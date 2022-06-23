<?php

namespace App\View\Tasks;

class Form extends \App\View\Main
{
    public function content(array $data)
    {
        $isNew = ! isset($data['data']['id']);

        
        $datePlan = new  \DateTime($data['data']['date_plan'] ?? 'now');
        $data['data']['date_plan'] = $datePlan->format('d.m.Y.H:i');
        ?>

            <div class="row">
                <div class="col-lg-12">
                    <div class="block">
                        <div class="block-content block-content-narrow">
                            <form class="form-horizontal push-10-t" action="<?= $isNew ? '/tasks/add' : '/tasks/update?id=' .$data['data']['id'] ?>" method="post">
                                <div class="form-group  <?= isset($data['messages']['id_account']) ? 'has-error' : '' ?> ">
                                    <div class="col-sm-9">
                                        <div class="form-material">
                                            <select class="form-control" id="material-title" name="id_account" size="1">
                                                <?php foreach($data['accounts'] as $account) :?>
                                                    <option value="<?= $account['id'] ?>" <?= isset($data['data']['id_account'] ) && $data['data']['id_account'] == $account['id'] ? 'selected' : ''?>><?= $account['login'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label for="material-title">Accounts</label>
                                            <?php if(isset($data['messages']['id_account'])) : ?>
                                                <div class="help-block"><?= $data['messages']['id_account'] ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div> 
                                <div class="form-group">
                                            <div class="col-sm-9">
                                                <div class="js-datetimepicker form-material input-group date" data-show-today-button="true" data-show-clear="true" data-show-close="true" data-format="DD.MM.YYYY HH:mm">
                                                    <input class="form-control" type="text" id="example-datetimepicker7" name="date_plan" placeholder="Choose a date of publish" value="<?= $data['data']['date_plan'] ?? '' ?>">
                                                    <label for="example-datetimepicker7">Date of publish</label>
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                <div class="form-group  <?= isset($data['messages']['title']) ? 'has-error' : '' ?> ">
                                    <div class="col-sm-9">
                                        <div class="form-material">
                                            <input class="form-control" type="text" id="material-title" name="title" placeholder="Please enter your title" value="<?= $data['data']['title'] ?? '' ?>">
                                            <label for="material-title">Title</label>
                                            <?php if(isset($data['messages']['title'])) : ?>
                                                <div class="help-block"><?= $data['messages']['title'] ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group  <?= isset($data['messages']['description']) ? 'has-error' : '' ?> ">
                                    <div class="col-sm-9">
                                        <div class="form-material">
                                            <textarea class="form-control" id="material-login" rows="5" name="description" placeholder="Please enter your description"><?= $data['data']['description'] ?? '' ?></textarea>
                                            <label for="material-login">Description</label>
                                            <?php if(isset($data['messages']['description'])) : ?>
                                                <div class="help-block"><?= $data['messages']['description'] ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9">
                                        <button class="btn btn-sm btn-primary" type="submit"><?= $isNew ? 'Create' : 'Save'?></button>
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

