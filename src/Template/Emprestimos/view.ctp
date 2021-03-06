<?php
/**
  * @var \App\View\AppView $this
  */
?>
<ul class="side-nav">    
    <?php if ($emprestimo->situacao == 'Pendente') { ?>
        <li><?= $this->Html->link(__('Devolver'), ['action' => 'finish', $emprestimo->id]) ?></li>
        <?php } ?>

        <li><?= $this->Html->link(__('Adicionar Ocorrência'), ['controller'=> 'Ocorrencias', 'action' => 'add', $emprestimo->id]) ?></li>
        <li><?= $this->Html->link(__('Editar'), ['action' => 'edit', $emprestimo->id]) ?></li>
        <li><?= $this->Form->postLink(__('Excluir'), ['action' => 'delete', $emprestimo->id], ['confirm' => __('Tem certeza que deseja excluir este empréstimo # {0}?', $emprestimo->id)]) ?></li>
    </ul>

<div class="emprestimos view large-11 medium-8 columns content">
    <h3><?= h($emprestimo->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('ID Empréstimo') ?></th>
            <td><?= $this->Number->format($emprestimo->id) ?></td>
        </tr>
          <tr>
            <th scope="row"><?= __('Número Patrimônio') ?></th>
            <td><?= $emprestimo->has('equipamento') ? $this->Html->link($emprestimo->equipamento->numeroPatrimonio, ['controller' => 'Equipamentos', 'action' => 'view', $emprestimo->equipamento->id]) : '' ?></td>
        </tr>
          <tr>
            <th scope="row"><?= __('Situação') ?></th>
            <td><?= h($emprestimo->situacao) ?></td>
        </tr> 
        <tr>
            <th scope="row"><?= __('Atendente') ?></th>
            <td><?= $emprestimo->has('usuario') ? $this->Html->link($emprestimo->usuario->nome, ['controller' => 'Usuarios', 'action' => 'view', $emprestimo->usuario->id]) : '' ?></td>
        </tr>
         <tr>
            <th scope="row"><?= __('Solicitante') ?></th>
             <td><?= $emprestimo->has('solicitante') ? $this->Html->link($emprestimo->solicitante->nome, ['controller' => 'Solicitantes', 'action' => 'view', $emprestimo->solicitante->id]) : '' ?></td>
        </tr>
        <tr>

            <!-- Se o empréstimo não estiver finalizado não imprime o nome do responsável e do solicitante -->
            <?php if ($emprestimo->situacao == 'Devolvido') { ?>  
            <th scope="row"><?= __('Responsável pela Devolução') ?></th>
            <td><?= $emprestimo->has('usuario') ? $this->Html->link($responsavel->nome, ['controller' => 'Usuarios', 'action' => 'view', $responsavel->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Solicitante/Aluno que Realizou a Devolução') ?></th>
            <td><?= h($emprestimo->nomeDevolveu) ?></td>
        </tr>        
        <?php } ?>

        <tr>
            <th scope="row"><?= __('Data Retirada') ?></th>
            <td><?= h(date('d/m/Y H:i', strtotime($emprestimo->dataRetirada))) ?></td>
        </tr>
        <tr>

            <!-- Se o empréstimo não estiver finalizado não imprime a data de devolução -->
            <?php if ($emprestimo->situacao == 'Devolvido') { ?> 
            <th scope="row"><?= __('Data Devolução') ?></th>
            <td><?= h(date('d/m/Y H:i', strtotime($emprestimo->dataDevolucao))) ?></td>
        </tr>
        <?php } ?>
    </table>
    <div class="related">
        <h4><?= __('Acessórios Relacionados') ?></h4>
        <?php if (!empty($emprestimo->acessorios)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Nome') ?></th>
                <th scope="col"><?= __('Criado') ?></th>
                <th scope="col"><?= __('Modificado') ?></th>
                <th scope="col" class="actions"><?= __('Opções') ?></th>
            </tr>
            <?php foreach ($emprestimo->acessorios as $acessorios): ?>
            <tr>
                <td><?= h($acessorios->id) ?></td>
                <td><?= h($acessorios->nome) ?></td>
                <td><?= h(date('d/m/Y H:i', strtotime($acessorios->created))) ?></td>
                <td><?= h(date('d/m/Y H:i', strtotime($acessorios->modified))) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('Detalhes'), ['controller' => 'Acessorios', 'action' => 'view', $acessorios->id]) ?>
                    <?= $this->Html->link(__('Editar'), ['controller' => 'Acessorios', 'action' => 'edit', $acessorios->id]) ?>
                    <?= $this->Form->postLink(__('Excluir'), ['controller' => 'Acessorios', 'action' => 'delete', $acessorios->id], ['confirm' => __('Tem certeza que deseja excluir este acessório # {0}?', $acessorios->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>

   <div class="related">
        <h4><?= __('Ocorrências Relacionadas') ?></h4>
        <?php if (!empty($emprestimo->ocorrencias)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('ID Ocorrência') ?></th>
                <th scope="col"><?= __('ID Empréstimo') ?></th>
                <th scope="col"><?= __('Descrição') ?></th>
                <th scope="col"><?= __('Providência') ?></th>
                <th scope="col" class="actions"><?= __('Opções') ?></th>
            </tr>
            <?php foreach ($emprestimo->ocorrencias as $ocorrencias): ?>
            <tr>
                <td><?= h($ocorrencias->id) ?></td>
                <td><?= h($ocorrencias->idEmprestimo) ?></td>
                <td><?= h($ocorrencias->descricao) ?></td>
                <td><?= h($ocorrencias->providenciaTomada) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('Detalhes'), ['controller' => 'Ocorrencias', 'action' => 'view', $ocorrencias->id]) ?>
                    <?= $this->Html->link(__('Editar'), ['controller' => 'Ocorrencias', 'action' => 'edit', $ocorrencias->id]) ?>
                    <?= $this->Form->postLink(__('Excluir'), ['controller' => 'Ocorrencias', 'action' => 'delete', $ocorrencias->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ocorrencias->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>

</div>



