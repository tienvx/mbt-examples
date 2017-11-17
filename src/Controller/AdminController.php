<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Component\Process\Process;
use Tienvx\Bundle\MbtBundle\Entity\Task;

class AdminController extends BaseAdminController
{
    public function startAction()
    {
        $id = $this->request->query->get('id');
        /** @var Task $entity */
        $entity = $this->em->getRepository('TienvxMbtBundle:Task')->find($id);
        $entity->setProgress(0);
        $this->em->flush();

        $model = $entity->getModel();
        $algorithm = $entity->getAlgorithm();
        $process = new Process("bin/console mbt:test {$model} --traversal='{$algorithm}'");
        $process->run();

        // redirect to the 'list' view of the given entity
        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'entity' => $this->request->query->get('entity'),
        ));
    }
}
