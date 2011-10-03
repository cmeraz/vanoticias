<?php
/*
Plugin Name: Asignar categorias a Usuarios
Plugin URI: http://plugin-wordpress.netsons.org
Description: this plugin allows to assign categories to users. view, edit and delete posts only categories selected for user
Version: 1.1
Author: Antonio Fortunato
Author URI: http://plugin-wordpress.netsons.org



    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

class UserCategory {

    function uscat_getAllCategories() {
        return get_categories(array('hierarchical' => 1,'hide_empty' => false));
    }

    function uscat_where($where) {
        if(UserCategory::uscat_notAdmin() && preg_match("#/wp-admin/edit.php#", $_SERVER['REQUEST_URI']) ) {
            //        if(UserCategory::uscat_notAdmin() && strstr($_SERVER['REQUEST_URI'], '/wp-admin/edit.php', true) != "") {

            global $wpdb;
            global $userdata;

            //recuper gli id delle categorie dell utente
            $cat_user = get_option("category_role_user$userdata->ID");
            $post_author = " ($wpdb->posts.post_author = $userdata->ID)";
            if($cat_user == "no") {
                return $where .= " AND ".$post_author;
            }


            if($cat_user != "" && $cat_user != null && $cat_user != "no") {


                //id delle categorie da escludere...
                if($cat_user[count($cat_user)-1] < 0) {
                    array_pop($cat_user);
                    $ids = get_objects_in_term($cat_user, 'category');
                    $out_posts = implode(', ', $ids);
                    $where .= " AND ($wpdb->posts.ID NOT IN ($out_posts) OR $post_author)";

                    return $where;
                }
                array_pop($cat_user);
                $ids = get_objects_in_term($cat_user, 'category');

                if ( count($ids) <= 0 ) {
                    //No Permissions, remove all posts
                    $ids = array(-1);
                }
                //stringa di id
                $out_posts = implode(', ', $ids);

                return $where .= " AND ($wpdb->posts.ID IN ($out_posts) OR  $post_author)";
            }
        }
        return $where;
    }

    function uscat_notAllowed($page) {
        return "<div class=\"error\"><p><strong>Non hai i privilegi per modificare il post.</strong><br />
				<a href=\"edit.php\">Return all'elenco dei post.</a></p></div>";
    }


    function uscat_categoryPermission($categories_current,$category_user) {

        $permission = true;
        //verifico se le categorie sono permesse o non permesse
        //-1 = exclude
        //-2 = exclude + create and edit...
        //1 = allow
        //2 = allow + create and edit

        if($category_user[count($category_user)-1] < 0) {
            $permission = false;
        }
        //tolgo il type dall array
        array_pop($category_user);

        foreach( $categories_current as $cat) {
            if(in_array($cat->cat_ID,$category_user) == $permission) {
                return true;
            }
        }
        return false;
    }

    function uscat_loadPost($in) {
        if(UserCategory::uscat_notAdmin()) {
            //        if(UserCategory::uscat_notAdmin() && strstr($_SERVER['REQUEST_URI'], '?action=edit&post=', true) != "") {
            global $userdata;
            $post = get_post($_GET['post'], ARRAY_A);
            $permission = true;
            $cat_user = null;
            //verifico se l'autore coincide
            if($post['post_author'] != $userdata->ID) {
                //recupero l'array con gli id delle categorie dell'utente
                $cat_user = get_option("category_role_user$userdata->ID");
                if($cat_user == "no") {
                    $permission = false;
                }else
                if($cat_user != null) {
                    //recupero le categorie del post corrente
                    $category = get_the_category($_GET['post']);
                    $permission = UserCategory::uscat_categoryPermission($category, $cat_user);
                    //controllo se ha i privilegi per modificare il post
                }
            }
            if(!$permission) {
                //se non ha i permessi mostro un messaggio
                ob_start(array('UserCategory', 'uscat_notAllowed'));
            }else {
                add_action('admin_footer-post.php', array('UserCategory','uscat_excludeCategories'));
                //  UserCategory::uscat_excludeCategories($cat_user);
            }
        }
        return $in;
    }

    /*
     * exclude categories in new post and edit post
    */
    function uscat_excludeCategories() {
        global $userdata;
        //recupero l'array con gli id delle categorie dell'utente
        $cat_user = get_option("category_role_user$userdata->ID");
        //verifico se le categorie sono permesse o non permesse -1 = exclude
        if($cat_user[count($cat_user)-1] == "-2") {
            //tolgo il type dall array
            array_pop($cat_user);

            ?>
<script type="text/javascript">
    jQuery(document).ready(function($){
            <?php foreach($cat_user as $cs) {  ?>
                    $("#in-category-<?php echo $cs ?>").parent("label").hide();
                <?php } ?>
                    });
</script>

            <?php
        }else if($cat_user[count($cat_user)-1] == "2") {
            //tolgo il type dall array
            array_pop($cat_user);
            ?>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $("#categorychecklist label").hide();
            <?php foreach($cat_user as $cs) {  ?>
                    $("#in-category-<?php echo $cs ?>").parent("label").show();
                <?php } ?>
                    });
</script>
            <?php
        }

    }

    function uscat_delPost($id) {
        if (UserCategory::uscat_notAdmin()) {
            global $wpdb;
            global $userdata;
            $article=get_post($id);

            $cat_user = get_option("category_role_user$userdata->ID");


            if ($catUser != null) {
                $cats = get_the_category($id);

                if (!UserCategory::allowCategoryPermission($cats,$cat_user)) {
                    die("No estas autorizado para eliminar el post de esta categoria.");
                }
            }
        }
    }

    function uscat_notAdmin() {
        //prendo il livello
        $level = get_option("category_role_level");
        if(!$level) {
            $level = 7;
        }
        global $userdata;
        return ($userdata->user_level <= $level);
    }

    function uscat_form() {
        global $wpdb;
        if (isset($_POST['submit_level'])) {
            $updated = UserCategory::uscat_saveLevel($_POST);
            if ($updated) {
                echo "<div class=\"updated\"><p><strong>" . __('Configuracion Guardada.', 'uscat_option') ."</strong></p></div>";
            } else {
                echo "<div class=\"error\"><p><strong>" . __('Ocurrio un error al guardar.', 'uscat_option') ."</strong></p></div>";
            }
        }else {
            if (isset($_POST['save_category'])) {
                $updated = UserCategory::uscat_saveCategory($_POST);
                if ($updated) {
                    echo "<div class=\"updated\"><p><strong>" . __('Configuracion Guardada.', 'uscat_option') ."</strong></p></div>";
                } else {
                    echo "<div class=\"error\"><p><strong>" . __('Ocurrio un error mientras se guardaba.', 'uscat_option') ."</strong></p></div>";
                }
            }
        }
        if (function_exists('get_authors')) {
            //prendo gli autenti
            $wpusers = get_authors();
            $userIds = array();
            foreach ($wpusers as $wpuser) {
                //TODO: When wordpress implement get_autors, check this works
                $userIds[] = $wpuser->ID;
            }
        } else { //prendo gli autenti
            $userIds = $wpdb->get_col("SELECT ID FROM $wpdb->users ORDER BY user_nicename;");
        }
        $users = array();

        //prendo il livello
        $level = get_option("category_role_level");
        if(!$level) {
            $level = 7;
        }
        //prendo le categorie
        $cats = UserCategory::uscat_getAllCategories();

        $opts = get_option("category_role");
        ?>
        
        
<h2>Asignar Categorías a los Usuarios</h2>
<div style="margin-bottom: 150px;"></div>

<!--LEYENDA DE LA DERECHA-->
<!--
<div align="right">
    <div align="left" style="margin:20px;width:200px">
        More information: Visit the <a href="http://www.plugin-wordpress.netsons.org/assign-users-categories-plugin-wordpress/">homepage</a> of the plugin for more information or for the most recent version of this plugin. Would you like to thank me? Donate.
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
            <input type="image" src="https://www.paypal.com/it_IT/IT/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - Il sistema di pagamento online più facile e sicuro!">
            <img alt="" border="0" src="https://www.paypal.com/it_IT/i/scr/pixel.gif" width="1" height="1">
        </form>
    </div>
</div> -->

<!--<h3>Allow Plugin for Users level:</h3>-->
<h3>Seleccione un Nivel de Usuario para Aplicar:</h3>
<form method="post" action="">
    <select name="level">
        <option value="10"<?php if($level == 10)echo "selected"?> ><= 10(Administrator)</option>
        <option value="9" <?php if($level == 9)echo "selected"?> ><= 9</option>
        <option value="8" <?php if($level == 8)echo "selected"?> ><= 8</option>
        <option value="7" <?php if($level == 7)echo "selected"?> ><= 7(Editor)</option>
        <option value="6" <?php if($level == 6)echo "selected"?> ><= 6</option>
        <option value="5" <?php if($level == 5)echo "selected"?> ><= 5</option>
        <option value="4" <?php if($level == 4)echo "selected"?> ><= 4</option>
        <option value="3" <?php if($level == 3)echo "selected"?> ><= 3</option>
        <option value="2" <?php if($level == 2)echo "selected"?> ><= 2(Autor)</option>
        <option value="1" <?php if($level == 1)echo "selected"?> ><= 1(Contributor)</option>
    </select> <input type="submit" value="ok" name="submit_level"/>
</form><br /><br />
<fieldset>
    <legend>Users</legend>
    <form method="post" action="">
        <select name="user" >
                    <?php foreach ($userIds as $userId) {
                        if($userId != 1) {
                            $selected = "";
                            $tmp_user = new WP_User($userId);
                            //se l'utente è maggiore del livello scelto non viene visualizzato
                            if($tmp_user->user_level <= $level ) {
                                if($_POST["user"] == "$tmp_user->ID") {
                                    $selected = "selected";
                                }
                                echo "<option $selected value=\"$tmp_user->ID\">lv:$tmp_user->user_level, $tmp_user->user_nicename ";
                            }
                        }
                    }?>
        </select>
        <input type="submit" value="ok" />
    </form>
</fieldset>
        <?php //mostro le categorie
        if($_POST["user"]) {
            //recupero gli id delle categorie dell utente
            $cat_user = get_option("category_role_user".$_POST["user"]);
            if($cat_user == "no") {
                $no_categories = "checked";
                $disabled = "disabled";
            }
            if($cat_user == null || !is_array($cat_user)) {
                $cat_user = array();
            }

            $type = $cat_user[count($cat_user)-1];
            ?>

<script type="text/javascript">
    jQuery(document).ready(function($){
        $(".disable").click(function () {

            if($("#categorychecklist input").attr("disabled")){
                $("#categories-all input").removeAttr("disabled");
            }
            else{
                $("#categories-all input").attr("disabled","disabled");
                $(this).removeAttr("disabled");
            }
        });
    });
</script>
<form method="post" action="">
    <input type="hidden" name="user_id" value="<?php echo $_POST["user"]?>" />    
    <div id="categorydiv" class="postbox" style="width:60%; float:left">
        <div id="categories-all" class="tabs-panel" style="height:450px">
            <label class="selectit">
                <input id="no_categories" class="disable" name="no_categories" <?php echo $no_categories;?> type="checkbox" value="1" /><strong>NINGUNA CATEGORIA</strong>
            </label>
            <label class="selectit">
                <input id="all_categories" class="disable" name="all_categories" type="checkbox" <?php echo $disabled;?> value="1" /><strong>TODAS LAS CATEGORIAS</strong>
            </label>
            <ul id="categorychecklist" class="list:category categorychecklist form-no-clear">
                            <?php
                            $category_selected= array();
                            foreach($cats as $ct) {
                                $checked = "";
                                if(in_array($ct->cat_ID,$cat_user)) {
                                    $checked ="checked";
                                    $category_selected[$ct->cat_ID]["name"]=$ct->cat_name;
                                }?>


                <li><label class="selectit">
                                        <?php
                                        echo "<input name=\"cat[]\" type=\"checkbox\" $disabled $checked value=\"$ct->cat_ID\">$ct->cat_name";
                                        ?>
                    </label></li>
                                <?php } ?>
            </ul>
        </div>
    </div>
    <div>
        <label class="selectit"><input type="radio" name="type" value="1" checked />Permitir categorías seleccionadas.</label><br />
        <label class="selectit"><input type="radio" name="type" value="2"  <?php if($type == 2) echo "checked"; ?> />Permitir categorias seleccionadas y excluir otras categorias al crear y editar posts.</label><br />
        <label class="selectit"><input type="radio" name="type" value="-1" <?php if($type == -1) echo "checked"; ?> />Excluir categorías seleccionadas.</label><br />
        <label class="selectit"><input type="radio" name="type" value="-2" <?php if($type == -2) echo "checked"; ?> />Excluir categorias seleccionadas y excluir otras categorias al crear y editar posts.</label><br />
        <input type="submit" value="save" name="save_category"/></div>
    <h3>Categorías Seleccionadas:</h3>
                <?php foreach($category_selected as $k => $cat) {
                    echo "- id: ".$k." Name: ".$cat["name"]."<br />";
                }?>
</form>
            <?php }

    }


    //salvo nel db le info...
    function uscat_saveCategory() {
        //esclude le categorie selezionate
        if(isset($_POST['all_categories'])) {
            update_option("category_role_user".$_POST['user_id'],"");
            return true;
        }
        if(isset($_POST['no_categories'])) {
            update_option("category_role_user".$_POST['user_id'],"no");
            return true;
        }
        //add type in the array...
        array_push($_POST['cat'], $_POST['type']);


        //    var_export($_POST['cat']);

        update_option("category_role_user".$_POST['user_id'], $_POST['cat']);

        return true;
    }
    //salvo nel db il livello degli user..
    function uscat_saveLevel() {
        //esclude le categorie selezionate
        if(isset($_POST['level'])) {
            update_option("category_role_level",$_POST['level']);
            return true;
        }
        return false;

    }

    //aggiunge il plugin nella sezione utenti
    function uscat_menu() {
        add_submenu_page('users.php', 'UserCategory', 'Asignar categorias a Usuarios', 10, basename(__FILE__), array('UserCategory', 'uscat_form'));
    }

}

add_action('admin_menu', array('UserCategory','uscat_menu'));

if(is_admin()) {
    add_action('load-post.php', array('UserCategory','uscat_loadPost'));
    add_action('admin_footer-post-new.php', array('UserCategory','uscat_excludeCategories'));
    add_action('posts_where', array('UserCategory','uscat_where'));
}