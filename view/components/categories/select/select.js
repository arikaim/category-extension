'use strict';

arikaim.component.onLoaded(function(component) {
    var select;

    component.setValue = function(val) {
      select.setValue(val);
    };

    component.destroy = function() {      
      select.destroy();
    };

    component.getValue = function() {  
      return select.value;
    };

    component.clear = function() {
      select.setValue('');

      var event = new Event('change');     
      select.el.dispatchEvent(event);      
    };

    component.addOption = function(items) {
      select.addOption(items);
    };

    component.removeOption = function(val) {
      select.removeOption(val);
    };

    component.reinit = function() {
      select.destroy();
      component.init();
    };

    component.init = function() {        
      var el = document.getElementById(component.getId());
      var clearButton = $('#' + component.getId() + '_clear_button');
      arikaim.ui.button(clearButton,function() {
        component.clear();
      });
      
      select = new HSSelect(el);
    };
    
    component.init();
 
    return component;
});
