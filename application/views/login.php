<h1>Login</h1>
Trage die Logindaten ein, mit denen du dich angemeldet hast und klicke auf
"Login".<br /><br />
<?=$this->LoginForm->renderFormOpen();?>
<table>
    <tr>
        <td>
            <? 
                if($this->LoginForm->isValide("email") === false){
                    echo "<b style='color: rgb(255,0,0);'>";
                }  
            ?>
            <?=$this->LoginForm->renderLabel("email");?>:
            <? 
                if($this->LoginForm->isValide("email") == false){
                    echo "</b>";
                }
            ?>
        </td>
        <td>
            <?=$this->LoginForm->renderField("email");?>
        </td>
    </tr>
    <tr>
        <td>
            <? 
                if($this->LoginForm->isValide("pw1") === false){
                    echo "<b style='color: rgb(255,0,0);'>";
                }   
            ?>
            <?=$this->LoginForm->renderLabel("pw1");?>:
            <? 
                if($this->LoginForm->isValide("email") == false){
                    echo "</b>";
                }
            ?>
        </td>
        <td>
            <?=$this->LoginForm->renderField("pw1");?>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
            <?=$this->LoginForm->renderField("submited");?>
        </td>
    </tr>
</table>
</Form>