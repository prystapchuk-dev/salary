<?php

namespace Cms\Model\Menu;

use Engine\DI\DI;
use Engine\Model;
use Cms\Model\Menu\Menu;

class MenuRepository extends Model
{
    public function add($params = [])
    {
        if (empty($params)) {
            return 0;
        }

        $menu = new Menu();
        $menu->setName($params['name']);
        $menuId = $menu->save();

        return $menuId;
    }

    public function getList()
    {
        $query = $this->db->query(
            $this->queryBuilder
                ->select()
                ->from('menu')
                ->sql()
            );

            return $query;
    }

}