<?php

/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class MyModule extends Module
{

    public function __construct()
    {
        $this->name = 'mymodule';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'Thiago Ferreira';

        parent::__construct();
        $this->displayName = $this->l('My example module');
        $this->description = $this->l('This is my first module!');
        $this->ps_versions_compliancy = ['min' => '1.7.6.0', 'max' => _PS_VERSION_];
    }

    public function install()
    {


        return parent::install()
            &&
            $this->registerHook('displayHeader') && $this->registerHook('displayProductAdditionalInfo');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }
    // public function hookdisplayHome($params){
    //     $db = \Db::getInstance();
    //     $request = "select * from ps_product";

    //     /** @var array $result */
    //     $result = $db->
    //   $this->dd($result);
    //     // die();
    // }

    public function hookDisplayLeftColumnProduct($params)
    {

        return  'HELLO FROM DISPLAY LEFT COLUMN PRODUCT HOOK!!';
    }

    public function hookdisplayHeader($params)
    {

        $this->context->controller->addJS($this->local_path . '/views/js/script.js');
    }
    public function hookdisplayProductAdditionalInfo($params)
    {
        $lang = Context::getContext()->language->id;
        $shop = new Shop((int)$this->context->shop->id);
        //get category id and manufacter id


        $categoryID = array_filter(Category::searchByName($lang, 'men'), fn ($cat) => $cat['name'] === 'Men')[0]['id_category'];

        $manufacterName = $params['product']['manufacturer_name'];

        $manufacterId = Manufacturer::getIdByName($manufacterName);

        //get products by category and manufacter;

        $products = Product::getProducts(Context::getContext()->language->id, 0, 0, 'id_product', 'ASC',  $categoryID, $manufacterId);
        

        foreach ($products as $product) {
            

            //Retrieve product attributes

            $productAttributes = Product::getAttributesInformationsByProduct($product['id_product']);
            //Get all attributes groups in an array
            //ex: [color,size] 
            $attGroups = array_unique(array_map(
                fn ($att) =>
                $att['id_attribute_group'],
                $productAttributes
            ));
            // Array where each index is 
            $arrAttByGroup = [];
            foreach ($attGroups as $att) {
                
                $arrAtt = array_filter($productAttributes, fn($attPr)=> $attPr['id_attribute_group'] === $att);
                
                array_push($arrAttByGroup, $arrAtt );
            }
            dump($arrAttByGroup);
         
            
        }






        $base_url = $shop->getBaseURL();

        $ajax = $base_url . 'modules/' . $this->name . '/ajax.php';
      
        $this->context->smarty->assign([
            'myparamtest' => $idProduct,
        ]);

        return $this->display(__FILE__, 'views/templates/hooks/hook.tpl');
    }
    public function hookdisplayProductActions()
    {

        return 'HELLO FROM DISPLAY PRODUCT ACTIONS';
    }

    public function addToCart($productId)
    {

       
    }

    // public function hookdisplayReassurance($params){
    //     var_dump($params) ;
    //     die();
    //     $this->context->smarty->assign([
    //         'myparamtest' => 'thiago ferreira',
    //     ]);

    //     return $this->display(__FILE__,'views/templates/hooks/reassurance.tpl');

    // }

    private function dd($value)
    {

        echo '<pre>';
        var_dump($value);

        echo '<pre>';

        die;
    }
}
