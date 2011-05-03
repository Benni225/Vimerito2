<h1>Registrieren</h1>
Schön, dass du dich registrieren möchtest. Bitte fülle das kleine Formular aus
und klicke auf registrieren.<br /><br />
<?=$this->newText;?><br /><br />
<?=$this->registerForm->renderFormOpen();?>
<table>
    <tr>
        <td><?=$this->registerForm->renderLabel("email");?></td>
        <td><?=$this->registerForm->renderField("email");?></td>
    </tr>
    <tr>
        <td><?=$this->registerForm->renderLabel("pw1");?></td>
        <td><?=$this->registerForm->renderField("pw1");?></td>
    </tr>
    <tr>
        <td><?=$this->registerForm->renderLabel("pw2");?></td>
        <td><?=$this->registerForm->renderField("pw2");?></td>
    </tr>
    <tr>
        <td></td>
        <td><?=$this->registerForm->renderField("submited");?></td>
    </tr>
</table>
</Form>