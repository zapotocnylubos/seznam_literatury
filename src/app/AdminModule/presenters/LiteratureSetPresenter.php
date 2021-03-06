<?php

namespace App\AdminModule\Presenters;


use App\AdminModule\Forms\LiteratureSetFormFactory;
use App\Models\LiteratureSetManager;

final class LiteratureSetPresenter extends BasePresenter
{
    /** @var LiteratureSetFormFactory */
    private $literatureSetFactory;

    /**  @var LiteratureSetManager */
    private $literatureSetManager;

    public function __construct(LiteratureSetFormFactory $literatureSetFactory, LiteratureSetManager $literatureManager)
    {
        parent::__construct();
        $this->literatureSetFactory = $literatureSetFactory;
        $this->literatureSetManager = $literatureManager;
    }

    public function actionUpdate($id)
    {
        $literatureSet = $this->literatureSetManager->getLiteratureSet($id);
        $this['literatureSetUpdateForm']->setDefaults($literatureSet);
    }

    public function renderList()
    {
        $this->template->literatureSets = $this->literatureSetManager->getLiteratureSets();
    }

    public function renderDetail($id)
    {
        $this->template->literatureSet = $literatureSet = $this->literatureSetManager->getLiteratureSet($id);

        $this->template->literatureGroups = $literatureSet->related('literature_groups')
            ->order('sort_order', 'DESC');
    }

    public function renderDeleteConfirm($literatureSetId) {
        $this->template->literatureSet = $this->literatureSetManager->getLiteratureSet($literatureSetId);
    }

    public function handleDelete($id)
    {
        $this->literatureSetManager->deleteLiteratureSet($id);
        $this->flashMessage('Literární set byl smazán.');
        $this->redirect('LiteratureSet:list');
    }

    public function actionActiveUpdate()
    {
        $activeLiteratureSet = $this->literatureSetManager->getActiveLiteratureSet();
        $this['literatureSetActiveForm']->setDefaults([
            'literature_set_id' => $activeLiteratureSet ? $activeLiteratureSet->id : null
        ]);
    }

    public function renderActive()
    {
        $this->template->activeLiteratureSet = $this->literatureSetManager->getActiveLiteratureSet();
    }

    public function createComponentLiteratureSetCreateForm()
    {
        return $this->literatureSetFactory->create(function ($literature_set_id) {
            $this->flashMessage('Literární set byl vytvořen.');
            $this->redirect('LiteratureSet:detail', $literature_set_id);
        });
    }

    public function createComponentLiteratureSetUpdateForm()
    {
        return $this->literatureSetFactory->update(function ($literature_set_id) {
            $this->flashMessage('Literární set byl upraven.');
            $this->redirect('LiteratureSet:detail', $literature_set_id);
        });
    }

    public function createComponentLiteratureSetActiveForm()
    {
        return $this->literatureSetFactory->active(function () {
            $this->flashMessage('Aktivní literární set byl upraven.');
            $this->redirect('LiteratureSet:active');
        });
    }
}
