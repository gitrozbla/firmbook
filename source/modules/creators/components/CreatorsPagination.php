<?php

class CreatorsPagination extends CPagination 
{
    public $limit = false;
    
    public function createPageUrl($controller,$page)
    {
        $params=$this->params===null ? $_GET : $this->params;
        $params[$this->pageVar]=$page+1;
        return $controller->mapPage($this->route.'~'
                .Yii::t('CreatorsModule.navigation', 'page').'-'.($page+1));
    }
    
    public function getLimit()
    {
        if ($this->limit) {
            $lastPage = ceil($this->limit / $this->pageSize);
            if ($this->currentPage < $lastPage - 1) {
                return $this->getPageSize();
            } else {
                return $this->limit % $this->pageSize;
            }
        } else {
            return $this->getPageSize();
        }
    }
}
