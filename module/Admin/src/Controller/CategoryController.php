<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Category;
use Admin\Form\CategoryForm;
use Admin\Helper\ToSlug;
use Admin\Helper\TrunCate;

class CategoryController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Category manager.
     * @var Application\Service\CategoryManager
     */
    private $categoryManager;


    /**
     * Constructor is used for injecting dependencies into the controller.
     */
    public function __construct($entityManager, $categoryManager)
    {
        $this->entityManager = $entityManager;
        $this->categoryManager = $categoryManager;
    }

    public function indexAction()
    {

        $categories = $this->entityManager->getRepository(Category::class)->findAll();
        // Render the view template

        $category = $this->entityManager->getRepository(Category::class)
            ->findOneById(1);

        return new ViewModel([
            'categories' => $categories,
        ]);
    }

    public function viewAction()
    {
        $categoryId = $this->params()->fromRoute('id', -1);
        $category = $this->entityManager->getRepository(Category::class)->findOneById($categoryId);

        if ($category == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        // Render the view template
        $products = $category->getProducts();

        foreach ($products as $product) {
            $truncate = new TrunCate();
            $description = $truncate($product->getDescription(), 30);
            $product->setDescription($description);
        }

        return new ViewModel([
            'category' => $category,
            'products' => $products
        ]);
    }


    public function addAction()
    {

        $categories_for_select = $this->categoryManager->categories_for_select();

        $form = new CategoryForm($categories_for_select);

        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {
            //var_dump(2);die();

            // Get POST data.

            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {

                // Get validated form data.
                $data = $form->getData();
                $data['alias'] = $this->slug($data['name']);
                $data['parent_id'] = ($data['parent_id'] != "") ? $data['parent_id'] : 0;

                // Check if Category exists?
                if ($this->categoryManager->addNewCategory($data) != 0)
                    return $this->redirect()->toRoute('categories', ['action' => 'index']);
                else $form->get('name')->setMessages(['Category exists.']);
            }
        }
        // Render the view template.
        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editAction()
    {
        $categoryId = $this->params()->fromRoute('id', -1);

        $category = $this->entityManager->getRepository(Category::class)
            ->findOneById($categoryId);
        if ($category == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $categories_for_select = $this->categoryManager->categories_for_select();
        unset($categories_for_select[$categoryId]);

        $form = new CategoryForm($categories_for_select);

        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {
            //var_dump(2);die();

            // Get POST data.

            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {

                // Get validated form data.
                $data = $form->getData();
                $data['alias'] = $this->slug($data['name']);

                // Check if Category exists?
                if ($this->categoryManager->updateCategory($category, $data) != 0)
                    return $this->redirect()->toRoute('categories', ['action' => 'index']);
                else $form->get('name')->setMessages(['Category exists.']);
            }
        } else {
            $data = [
                'name' => $category->getName(),
                'description' => $category->getDescription(),
                'parent_id' => $category->getParentId(),
            ];
            $form->setData($data);
        }

        return new ViewModel([
            'form' => $form,
            'category' => $category
        ]);
    }

    public function deleteAction()
    {
        $CategoryId = $this->params()->fromRoute('id', -1);

        $category = $this->entityManager->getRepository(Category::class)
            ->findOneById($CategoryId);
        if ($category == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $this->categoryManager->removeCategory($category);

        // Redirect the user to "index" page.
        return $this->redirect()->toRoute('categories', ['action' => 'index']);
    }


    public function slug($str)
    {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }
}
