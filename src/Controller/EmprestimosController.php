<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Mailer\MailerAwareTrait;

/**
 * Emprestimos Controller
 *
 * @property \App\Model\Table\EmprestimosTable $Emprestimos
 */
class EmprestimosController extends AppController
{
    use MailerAwareTrait;

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */

    public function index()     //Lista apenas os empréstimos pendentes
    {
        $this->paginate = [
            'contain' => ['Equipamentos', 'Usuarios', 'Solicitantes']
        ];

       $pendentes = $this->Emprestimos->find('all', array(
        'conditions' => array('Emprestimos.situacao' => 'Pendente'),
        'order' => array('Emprestimos.dataRetirada' => 'desc')
    ));

     $this->set('pendentes', $this->paginate($pendentes));

        $this->set(compact('pendentes'));
        $this->set('_serialize', ['emprestimos']);
    }

    public function rotinaEmails(){

         $pendentes = $this->Emprestimos->find('all', array(
        'conditions' => array('Emprestimos.situacao' => 'Pendente')
    ));

         foreach ($pendentes as $pendente) {
             //if ($pendente->periodoEmail ) {
                 
            // }
         }


    }


       public function finalizados()    //Lista apenas os empréstimos finalizados
    {
        $this->paginate = [
            'contain' => ['Equipamentos', 'Usuarios', 'Solicitantes']
        ];
    $devolvidos = $this->Emprestimos->find('all', array(
        'conditions' => array('Emprestimos.situacao' => 'Devolvido'),
        'order' => array('Emprestimos.dataDevolucao' => 'desc')
    ));

     $this->set('devolvidos', $this->paginate($devolvidos));

        $this->set(compact('devolvidos'));
        $this->set('_serialize', ['devolvidos']);
    }

     public function isAuthorized($usuario)
{
    if (isset($usuario['tipo']) && $usuario['tipo'] === 'Administrador') {
        return true;
    }else if(in_array($this->request->action, ['index', 'finalizados', 'finish', 'add', 'logout', 'view'])){
    return true;
}
return false;
}

    /**
     * View method
     *
     * @param string|null $id Emprestimo id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $emprestimo = $this->Emprestimos->get($id, [
            'contain' => ['Acessorios', 'Ocorrencias', 'Equipamentos', 'Usuarios', 'Solicitantes']
        ]);

    // //Busca o nome do responsável pelo ID
        $resp = $this->Emprestimos->Usuarios->findById($emprestimo->responsavel_id);        
        $responsavel = $resp->first();
       
        $this->set(compact('emprestimo', 'responsavel'));
        $this->set('_serialize', ['emprestimo']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */

    public function finish($id = null){

        $emprestimo = $this->Emprestimos->get($id, [
            'contain' => ['Acessorios', 'Usuarios']
        ]);

            //Busca o solicitante pelo ID
            $solic = $this->Emprestimos->Solicitantes->findById($emprestimo->solicitante_id);
            $solicitante = $solic->first();

            //Busca o equipamento pelo ID
            $equip = $this->Emprestimos->Equipamentos->findById($emprestimo->equipamento_id);
            $equipamento = $equip->first();


        if ($this->request->is(['patch', 'post', 'put'])  && $emprestimo->situacao == 'Pendente') {
            $emprestimo = $this->Emprestimos->patchEntity($emprestimo, $this->request->getData());
            if ($this->Emprestimos->save($emprestimo)) {

           $valorCheckbox = $_POST['emailFlag'];
            if($valorCheckbox == 1){  //1 = marcado   // 0 = desmarcado
                //Envia email ao solicitante
            $this->getMailer('Emprestimo')->send('receber', [$emprestimo, $solicitante, $equipamento]);
            }

                $this->Flash->success(__('Empréstimo finalizado com sucesso.'));

                return $this->redirect(['action' => 'finalizados']);
            }
            $this->Flash->error(__('O emprestimo não pôde ser finalizado. Por favor, tente novamente.'));
        }
        $this->set(compact('emprestimo', 'solicitante'));
        $this->set('_serialize', ['emprestimo']);
    }

    public function add()
    {
        $emprestimo = $this->Emprestimos->newEntity();
        if ($this->request->is('post')) {
            $emprestimo = $this->Emprestimos->patchEntity($emprestimo, $this->request->getData());

            //$eq = $this->Emprestimos->Equipamentos->get($this->request->data['equipamento_id']);
            if ($this->Emprestimos->save($emprestimo)) {

            //Busca o solicitante pelo ID
            $solic = $this->Emprestimos->Solicitantes->findById($emprestimo->solicitante_id);
            $solicitante = $solic->first();

            //Busca o equipamento pelo ID
            $equip = $this->Emprestimos->Equipamentos->findById($emprestimo->equipamento_id);
            $equipamento = $equip->first();

             $valorCheckbox = $_POST['emailFlag'];
            if($valorCheckbox == 1){  //1 = marcado   // 0 = desmarcado
                //Envia email ao solicitante
                $this->getMailer('Emprestimo')->send('emprestar', [$emprestimo, $solicitante, $equipamento]);
                 }
               
                $this->Flash->success(__('Empréstimo salvo com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('O empréstimo não pôde ser salvo. Por favor, tente novamente.'));
        }

        $acessorios = $this->Emprestimos->Acessorios->find('list', ['limit' => 200]);
        $solicitantes = $this->Emprestimos->Solicitantes->find('list', ['limit' => 200]);
        $equipamentos = $this->Emprestimos->Equipamentos->find('list', ['limit' => 200]);
        $usuarios = $this->Emprestimos->Usuarios->find('list', ['limit' => 200]);
        $this->set(compact('emprestimo', 'acessorios', 'solicitantes', 'equipamentos', 'usuarios'));
        $this->set('_serialize', ['emprestimo']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Emprestimo id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $emprestimo = $this->Emprestimos->get($id, [
            'contain' => ['Acessorios']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $emprestimo = $this->Emprestimos->patchEntity($emprestimo, $this->request->getData());
            if ($this->Emprestimos->save($emprestimo)) {
                $this->Flash->success(__('Empréstimo alterado com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('O empréstimo não pôde ser alterado. Por favor, tente novamente.'));
        }
        $acessorios = $this->Emprestimos->Acessorios->find('list', ['limit' => 200]);
        $solicitantes = $this->Emprestimos->Solicitantes->find('list', ['limit' => 200]);
        $this->set(compact('emprestimo', 'acessorios', 'solicitantes'));
        $this->set('_serialize', ['emprestimo']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Emprestimo id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $emprestimo = $this->Emprestimos->get($id);

        $ocorrencia = $this->Emprestimos->Ocorrencias->find('all', array(
        'conditions' => array('Ocorrencias.idEmprestimo' => $id)));
        foreach ($ocorrencia as $ocorrencia2) {
            if ($this->Emprestimos->Ocorrencias->delete($ocorrencia2)) {
                $this->Flash->success(__('Ocorrência '. $ocorrencia2->id . ' excluída.'));
                }
            }
        
            if ($this->Emprestimos->delete($emprestimo)) {
            $this->Flash->success(__('Empréstimo excluído com sucesso.'));
       } else {
            $this->Flash->error(__('O empréstimo não pôde ser excluído. Por favor, tente novamente.'));
         }

        return $this->redirect(['action' => 'index']);
    }
}
