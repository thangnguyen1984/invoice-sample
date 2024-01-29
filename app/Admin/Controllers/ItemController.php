<?php

namespace App\Admin\Controllers;

use App\Item;
use App\Category;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ItemController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Item';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Item());

        $grid->column('id', __('Id'));
        $grid->category()->name();
        $grid->column('name', __('Name'));
        $grid->column('unit', __('Unit'));
        $grid->column('price', __('Price'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->disableExport();
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Item::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('cat_id', __('Cat id'));
        $show->field('name', __('Name'));
        $show->field('unit', __('Unit'));
        $show->field('price', __('Price'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Item());

        $catModel = new Category;
        $form->select('cat_id', __('Category'))->options($catModel::all()->pluck('name', 'id'))->required();
        $form->text('name', __('Name'))->required();
        $form->select('unit', __('Unit'))->options(array("kg"=>"kg","pcs"=>"pcs","pack"=>"pack"))->required();
        $form->text('price', __('Price'))->required();
        
        return $form;
    }
}
