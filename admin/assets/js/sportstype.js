/**
* @copyright    Copyright (C) 2005-2015 joomleague.at. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*
* MODIFICATED BY Various Pro Solution Systems
* Best Solutions: http://vpss.de
*/

Joomla.submitbutton = function(task) {
    var res = true;
    var validator = document.formvalidator;
    var form = $('adminForm');
    
    if (task == 'sportstype.cancel') {
        Joomla.submitform(task);
        return;
    }
    
    // do field validation
    if (validator.validate(form.name) === false) {
        alert(Joomla.JText._('COM_JOOMLEAGUE_ADMIN_SPORTSTYPE_CSJS_UNTRANSLATED_NAME'));
        form.name.focus();        
        res = false;
    }
    
    if (res) {
        Joomla.submitform(task);
    } else {
        return false;
    }        
}
