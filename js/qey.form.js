var validators = validators || {};

var qeyForm = function() {
    var $form = null;
    var self = this;
    
    this.beforeSubmit = function() {
        var result;
        $('[data-input-type=model-connector] .selected-items')
            .children().attr('selected', 'selected');
        $form.qeyValidator("submit", function(r){result = r});
        if (result) {
            //$form.find('.button').hide();
            $form.find('.qey-form-spinner').show();
            return true;
        } else {
            return false;
        }
    }
    
    this.dumpResponse = function(response) {
        $form.find('.qey-form-spinner').hide();
        $form.find('.qey-form-response').html(response);
    }
    
    this.initAjaxForm = function() {        
        $form.ajaxForm({
            data: {'qey-form-ajax': true},
            beforeSubmit: this.beforeSubmit,
            success: this.dumpResponse,
            context: this
        });
    };
    
    this.initModelConnector = function($parent){
        $selected = $('.selected-items', $parent);
        $source = $('.source-items', $parent);
        $add = $('.add', $parent);
        $remove = $('.remove', $parent);
        var setButtonStates = function() {
            if ($source.is(':empty')) {
                $add.attr('disabled', 'disabled');
            } else {
                $add.removeAttr('disabled');
            }
            
            if ($selected.is(':empty')) {
                $remove.attr('disabled', 'disabled');
            } else {
                $remove.removeAttr('disabled');
            }
        }
        
        var buttonAction = function($button, $source, $destination) {
            $selectedItems = $source.children('option:selected');
            $selectedItems.remove('option:selected').appendTo($destination);
            $('option', $parent).removeAttr("selected");
            setButtonStates();
            return false;
        }
        $('button.add', $parent).click(function() {
            return buttonAction($(this), $source, $selected);
        });
        
        $('button.remove', $parent).click(function() {
            return buttonAction($(this), $selected, $source);
        });
        setButtonStates();
    }
    
    this.initMultiInput = function($parent) {
        $empty = $('.multi-input-empty', $parent);
        var checkSubButtonVisibility = function() {
            $sub = $('.multi-input-sub', $parent);
            if ($sub.length == 2) {
                $sub.hide();
            } else {
                $sub.show();
            }
        }
        checkSubButtonVisibility();
        
        var removeFunction = function() {
            $(this).closest('.multi-input-entry').remove();
            checkSubButtonVisibility();
            return false;
        }
        $('.multi-input-sub', $parent).click(removeFunction);
        $('.multi-input-add', $parent).click(function() {
            $newItem = $empty.clone().insertBefore($(this)).show();
            $sub = $('.multi-input-sub').click(removeFunction);
            checkSubButtonVisibility();
            return false;
        });
    }
    
    this.init = function($aForm) {
        $form = $aForm;
        
        var id = $form.attr('id');
        var descriptor = models[id];

        $form.qeyValidator({
            descriptor: descriptor,
            validators: validators
        });

        if ($form.attr('data-qey-form') == 'ajax') {
            this.initAjaxForm();
        } else {
            $form.submit(this.beforeSubmit);
        }
        
        $('[data-qey-input-type=model-connector]', $form).each(function(){
            self.initModelConnector($(this));
        });
        $('[data-qey-input-type=multi-input]', $form).each(function(){
            self.initMultiInput($(this));
        });
    }
};


$(document).ready(function() {
    var $forms = $('[data-qey-form]');
    $forms.each(function() {
        var form = new qeyForm();
        form.init($(this));
    });
});