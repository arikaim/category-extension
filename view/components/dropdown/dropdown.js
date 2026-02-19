'use strict';

arikaim.component.onLoaded(function(component) {
    var dropdown;
    var inputValue;
    var label;

    component.getSelected = function() {
        return inputValue.val();
    }

    component.init = function() {
        var el = component.getElement();
        inputValue = $(el).find('.dropdown-value');
        label = $(el).find('.dropdown-label');
        dropdown = new HSDropdown(el);

        var categoryTree = arikaim.ui.getComponent('category_tree');
        
        categoryTree.onItemClick(function(item) {
            var dataValue = $(item.el).attr('data-value');
            inputValue.val(dataValue);
            label.val(item.data.path);
        });    
    }

    component.init();

    return component;
});