<?php
/**
  * @var \App\View\AppView $this
  */
?>

<div class="emprestimos form large-9 medium-8 columns content">
    <?= $this->Form->create($emprestimo) ?>
    <fieldset>
        <legend><?= __('Finalizar Empréstimo') ?></legend>
        <?php
            echo $this->Form->control('nomeDevolveu');
            echo $this->Form->control('nomeResponsavel', array('type' => 'hidden', 'value' => $usuarioLogado));
            echo $this->Form->control('situacao', array('type' => 'hidden', 'value' => 'Devolvido'));
            date_default_timezone_set('America/Sao_Paulo');
            $dataDevolucao = date('Y-m-d H:i');
            echo $this->Form->control('dataDevolucao', array('type' => 'hidden', 'value'=> $dataDevolucao));

            ?>

    </fieldset>
    <?= $this->Form->button(__('Finalizar')) ?>
    <?= $this->Form->end() ?>
</div>
