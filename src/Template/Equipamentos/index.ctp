<?php
/**
  * @var \App\View\AppView $this
  */
?>

<ul class="side-nav">    
        <li><?= $this->Html->link(__('Novo Equipamento'), ['action' => 'add']) ?></li>
    </ul>

<div class="equipamentos index large-11 medium-8 columns content">
    <h3><?= __('Equipamentos') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('Nome') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Número Patrimônio') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Criado') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Modificado') ?></th>
                <th scope="col" class="actions"><?= __('Opções') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($equipamentos as $equipamento): ?>
            <tr>
                <td><?= h($equipamento->nome) ?></td>
                <td><?= h($equipamento->numeroPatrimonio) ?></td>
                <td><?= h(date('d/m/Y H:i', strtotime($equipamento->created))) ?></td>
                <td><?= h(date('d/m/Y H:i', strtotime($equipamento->modified))) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('Detalhes'), ['action' => 'view', $equipamento->id]) ?>
                    <?= $this->Html->link(__('Editar'), ['action' => 'edit', $equipamento->id]) ?>
                    <?= $this->Form->postLink(__('Excluir'), ['action' => 'delete', $equipamento->id], ['confirm' => __('Tem certeza que deseja excluir este equipamento # {0}?', $equipamento->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('Primeira')) ?>
            <?= $this->Paginator->prev('< ' . __('Anterior')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('Próxima') . ' >') ?>
            <?= $this->Paginator->last(__('Última') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Página {{page}} de {{pages}}, mostrando {{current}} registro(s) de um total de {{count}}')]) ?></p>
    </div>
</div>
