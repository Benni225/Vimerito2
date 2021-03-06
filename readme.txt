**********************************************************************
**********************************************************************
Vimerito 2 v.0.6

Versiondetails:
**********************************************************************
1. Modules included. Modules are application parts which are completly 
   isolated. Controllers outside of a module can't use the moduls 
   classes. For modules it is possible to use external classes.
   The buildup of a module is completly like an application, but it 
   must be located inside of an application. 

2. The system-class VLang included. With this class you can controll
   various languages. For this you can use the database or an 
   INI-File.
   
3. Now you can call the VJavaScript::__construct() with a file,
   e.g.:
   $js = new VJavaScript("file:path/to/the/javascript.js");
   
   or with source:
   $js = new VJavaScript("script: document.body=''");
   
4. The activ-recorder can return JSON, now.

5. The big VEvent-Bug removed. Events now called.

6. VRouter, Vimerito, VRequest, VLayout adjusted for modules.

7. VRouter::route rewritten.

8. Vimerito::__autoload rewritten. Bugs removed.




Vimerito 2 v.0.5

Versiondetails:
**********************************************************************
1. Many Bugs had removed from classes
    - Vimerito
    - VQueryBuilder (Where-Clause, Notices removed)
    - VActiveRecorder (utf8-decoding removed)
    - VEvent (Notice removed)
    - VController (Notices removed)
    - VDatabase (the nonsense of utf8-encoding removed)
    - and many more
    
2. VActiveRecorder
    - In method findAll the parameter "order by" added
    - In method findLast the parameter "limit" and "order by" added
    - In method findeWhere the parameter "limit" and "order by" added
    
3. VQueryBuilder
    - limit and order by is implemented.
    
3. The eventhandling will now work.

4. Vimerito2 is now more stable.
    
**********************************************************************
**********************************************************************
Vimerito 2 v.0.4

Versiondetails:
**********************************************************************
Version 0.4
1. [new] Ressourcemanagement added
From version 0.4 on, vews and layouts handled as ressources.
 
2. [changed] VView.class.php completly rewritten
VView is no static lib anymore. 
Please use the following constructs:
$view = new VView; or  $view = new VView("viewfile.php");

//Viewloading
$view->load("viewfile.php");
//viewrendering
$view->render(cachemode);
//insert viewressource into the layout
VLayout::insertIntoBlock('blockname', $view->cachedView);

Now you also have to assign variables for views.

$view->assignVar('name', 'value');

or 

$view->assignVar(array(
    'name1' =>  'value1',
    'name2' =>  'value2'
));

In a viewfile you have access to the variables at this way:
<html>
    <head></head>
    <body>
        <div>
            <?=$this->name1;?>
        </div>
        <div>
            <?=$this->name2;?>
        </div>
    </body>
</html>

3. [new]    VHtmlElement.class.php added
Now, it is possible to create HTML-elements inside a controller 
and you can insert them into a view or the layout, via a css-selector.

$htmlE = new VHtmlElement;

$htmlE->tag     = "div";
$htmlE->name    = "name";
$htmlE->id      = "id";
$htmlE->src     = "src";
$htmlE->parent  = "cssSelector";
...

For setting attributes you normaly should configure with css you
can use the method addAttribute('attributeName', 'value');

$htmlE->addAttribute('width', '150');

You can remove such a attribute with removeAttribute('attributeName');

$htmlE->removeAttribute('width');

If you want to insert this element into a view or into a layout you
the insert-method.
$htmlE->insert($viewObject[, Append - Prepend - Replace]); or $htmlE->insert(Layout); 

$viewObject is a object of the class VView. Layout is a constant.

4. [new] VJavaScript added
Now you can script JavaScript inside your controller and send it 
to the layout or a view.

$js = new VJavaScript;
$js->setCode("
    //JavaScript code
");

$js->insert($viewObject[, Append - Prepend - Replace]); or $js->insert(Layout); 

6. Some bugs removed.

**********************************************************************
**********************************************************************
Vimerito 2 v.0.3

Versiondetails:
**********************************************************************
Version 0.3

1. [changed] many, many bugs in the class VQuerybuilder have removed
Puh! There were bugs in 
- the where-clause
- the set-clause
- the value-clause
- the insert-clause
- the update-clause

2. [new] cols-clause in the class VQuerybuilder
For insert-querys the cols-clause created. This clause create only 
brackets with columnnames.

3. [changed] a big bug in the class VAccessRights has removed
There was a bug while the authentication of the user.
Now it should run in all cases. 

4. [new] findAll in the ActivRecorder-class
The method findAll will find all datasets of a table.

5. [new] findLast added to the ActivRecorder

6. [new] insertForm addad to the ActivRecorder

7. [changed] the authenticationbug by running the application has 
             removed
             
8. [changed] insert- and updatebug removed in the class VDatabase

9. [changed] no warnings produced anymore by analysing the database
             in VDatabase

10. [changed] no warnings produced anymore by executing a query
              in VDatabase
              
11. [new] routing of applications added to VRouter
Now a website can subdivide into different applications. If you
configure the array "applications" in applicationConfiguration.php
at the way:
$applications = array(
        "nameOfApplication"     =>  "path/to/applicationdir"
    )
    
you can route to the application in the url:
www.yoursite.com/applicationame/controller/action.html

12. [new] third parameter to Vimerito::createUrl added
If you create a URL with this method now you can link to an 
application.
Vimerito::createUrl(array('controller', 'action', 'application'), array(parameter));




 
 
**********************************************************************
**********************************************************************
Vimerito 2 v.0.2

Versiondetails:
**********************************************************************
Version 0.2

1. [changed] file and class conventions
----------------------------------------------------------------------
The conventions for contoller, models and form changed. It might be 
more easier. 

If you create a controller in file
{applicationdir}/controllers/myStartSiteController.class.php
Now, the controllername is only:
myStartSite

For example, the controller have to look like:

class myStartSite extends VController{
    //some code
}

For forms and models it is the same:
{applicationdir}/forms/myLoginForm.class.php

class myLogin extends VForm{
    //some code
}

{applicationdir}/models/myTableModel.class.php

class myTable extends VActiceRecorder{
    //some code
}

2. [new] Eventhandling
----------------------------------------------------------------------
Eventhandling were added to Vimerito 2.
The classname is VEvent and the class is placed in the folder
{systemdir}/classes/VEvent.class.php

The following methods were added:

VEvent::add(String eventname, array callback)
    - eventname is the name of the event you want add 
    - callback is an array with the class an the method that have to 
      call by triggering an event. 
      array('class', 'method');
    Every event can get a endless number of classes an methods

VEvent::trigger(string eventname[, object controllerinstance]);
    - eventname is the name of the event you want trigger
    - controllerinstance (optional) is an instance of the controller 
      that triggered the event  

3. [changed] dirname for the controllerfolder
----------------------------------------------------------------------
The dirname for controller changed to "controllers"

4. [changed] conventions for controllermethods
----------------------------------------------------------------------
A method in a controller that should be able to route with the url
have to get a "Action" behind the methodname.
Look at the follwing example:
The url is:
http://www.mysite.com/myController/methodname.html

The belonging controller looks like:

class myController extends VController{
    public function myControllerInit(){
        //some code
    }

    public function methodnameAction(){
        //some code
    }
}
 