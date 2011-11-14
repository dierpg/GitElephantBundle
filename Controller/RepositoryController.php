<?php

namespace Cypress\GitElephantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class RepositoryController extends Controller
{
    /**
     * @Route("/", name="repository_root")
     * @Template("CypressGitElephantBundle:Repository:tree.html.twig")
     */
    public function rootAction(Request $request)
    {
        return array(
            'ref' => $this->get('session')->get('gitelephantbundle.branch', 'master'),
            'tree' => $this->getRepository()->getTree(),
            'branches' => $this->getRepository()->getBranches()
        );
    }

    /**
     * @Route("/tree/{ref}", name="repository_branch")
     * @Template("CypressGitElephantBundle:Repository:tree.html.twig")
     */
    public function branchAction($ref)
    {
        $this->get('session')->set('gitelephantbundle.branch', $ref);
        return array(
            'ref' => $ref,
            'tree' => $this->getRepository()->getTree('', $ref),
            'branches' => $this->getRepository()->getBranches()
        );
    }

    /**
     * @Route("/tree/{ref}/{treeish_path}", name="repository_tree", requirements={"treeish_path" = ".+"})
     * @Template()
     */
    public function treeAction(Request $request, $ref, $treeish_path)
    {
        return array(
            'ref' => $ref,
            'tree' => $this->getRepository()->getTree($treeish_path, $ref),
            'branches' => $this->getRepository()->getBranches()
        );
    }

    /**
     * @return \GitElephant\Repository
     */
    private function getRepository()
    {
        return $this->get('cypress_git_elephant.repository');
    }
}
