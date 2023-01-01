<?php

namespace App\Policies\admin;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;
    public function before( Admin $admin){
        if ($admin->owner == 1){
            return true;
        }
    }


    public function viewAdmin(Admin  $admin  )
    {

        $permissions = json_decode( $admin->permissions);
        $indix = array_search('admin',array_column($permissions,'0')) ;
        return ($permissions[$indix]->v ? true : false);
    }
    public function addAdmin(Admin $admin)
    {

        $permissions = json_decode($admin->permissions);
        $indix = array_search('admin',array_column($permissions,'0')) ;
        return ($permissions[$indix]->a ? true : false);
    }
    public function updateAdmin( Admin $admin)
    {

        $permissions = json_decode($admin->permissions);
        $indix = array_search('admin',array_column($permissions,'0')) ;
        return ($permissions[$indix]->u ? true : false);
    }
    public function deleteAdmin( Admin $admin)
    {

        $permissions = json_decode($admin->permissions);
        $indix = array_search('admin',array_column($permissions,'0')) ;
        return ($permissions[$indix]->d ? true : false);
    }

    public function viewCategory(Admin $admin)
    {

        $permissions = json_decode($admin->permissions);
        $indix = array_search('category',array_column($permissions,'0')) ;
        return ($permissions[$indix]->v ? true : false);
    }
    public function addCategory(Admin $admin)
    {

        $permissions = json_decode($admin->permissions);
        $indix = array_search('category',array_column($permissions,'0')) ;
        return ($permissions[$indix]->a ? true : false);
    }
    public function updateCategory( Admin $admin)
    {

        $permissions = json_decode($admin->permissions);
        $indix = array_search('category',array_column($permissions,'0')) ;
        return ($permissions[$indix]->u ? true : false);
    }
    public function deleteCategory( Admin $admin)
    {

        $permissions = json_decode($admin->permissions);
        $indix = array_search('category',array_column($permissions,'0')) ;
        return ($permissions[$indix]->d ? true : false);
    }

    public function viewSeller(Admin $admin)
    {

        $permissions = json_decode($admin->permissions);
        $indix = array_search('seller',array_column($permissions,'0')) ;
        return ($permissions[$indix]->v ? true : false);
    }
    public function addSeller(Admin $admin)
    {

        $permissions = json_decode($admin->permissions);
        $indix = array_search('seller',array_column($permissions,'0')) ;
        return ($permissions[$indix]->a ? true : false);
    }
    public function updateSeller( Admin $admin)
    {

        $permissions = json_decode($admin->permissions);
        $indix = array_search('seller',array_column($permissions,'0')) ;
        return ($permissions[$indix]->u ? true : false);
    }
    public function deleteSeller( Admin $admin)
    {

        $permissions = json_decode($admin->permissions);
        $indix = array_search('seller',array_column($permissions,'0')) ;
        return ($permissions[$indix]->d ? true : false);
    }

    public function viewProduct(Admin $admin)
    {

        $permissions = json_decode($admin->permissions);
        $indix = array_search('product',array_column($permissions,'0')) ;
        return ($permissions[$indix]->v ? true : false);
    }
    public function addProduct(Admin $admin)
    {

        $permissions = json_decode($admin->permissions);
        $indix = array_search('product',array_column($permissions,'0')) ;
        return ($permissions[$indix]->a ? true : false);
    }
    public function updateProduct( Admin $admin)
    {

        $permissions = json_decode($admin->permissions);
        $indix = array_search('product',array_column($permissions,'0')) ;
        return ($permissions[$indix]->u ? true : false);
    }
    public function deleteProduct( Admin $admin)
    {

        $permissions = json_decode($admin->permissions);
        $indix = array_search('product',array_column($permissions,'0')) ;
        return ($permissions[$indix]->d ? true : false);
    }

    public function viewOrder(Admin $admin)
    {

        $permissions = json_decode($admin->permissions);
        $indix = array_search('order',array_column($permissions,'0')) ;
        return ($permissions[$indix]->v ? true : false);
    }
    public function updateOrder(Admin $admin)
    {

        $permissions = json_decode($admin->permissions);
        $indix = array_search('order',array_column($permissions,'0')) ;
        return ($permissions[$indix]->u ? true : false);
    }

    public function viewCurrencie(Admin $admin)
    {

        $permissions = json_decode($admin->permissions);
        $indix = array_search('currencie',array_column($permissions,'0')) ;
        return ($permissions[$indix]->v ? true : false);
    }
    public function updateCurrencie(Admin $admin)
    {
        $permissions = json_decode($admin->permissions);
        $indix = array_search('currencie',array_column($permissions,'0')) ;
        return ($permissions[$indix]->u ? true : false);
    }

}
