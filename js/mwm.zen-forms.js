!function(e){if(e.hasOwnProperty("expr")){var s=function(s){return s.is(":checkbox,:radio")&&s.parents("form:first,html:first").find("[name="+s[0].name+"]").length>1?s.parents("form:first,html:first").find("[name="+s[0].name+"]:checked").map(function(){return e(this).val()}):s.is(":checkbox,:radio")?s.filter(":checked").val():"val"in s?s.val():"text"in s?s.text():""},t=function(t,r,n,a){return a||(a=!1),"undefined"==typeof r[3]?a:n&&(n=s(e(n)),void 0===n)?a:r[3]&&e(r[3]).length?t(s(e(r[3])),n):r[3]?t(r[3],n):a};e.expr[":"]["in-list"]=function(s,r,n){return t(function(s,t){if(t.constructor===Array||t.constructor===e)return s.constructor!==Array&&s.constructor!==e&&(s=s.split(",")),e.grep(t,function(e){return-1!==s.indexOf(e)}).length>0;var r=s.constructor===Array?s:s.split(",");return-1!==e.inArray(t,r)},n,s)},e.expr[":"]["not-in-list"]=function(s,r,n){return t(function(s,t){return t.constructor===Array||t.constructor===e?(s.constructor!==Array&&s.constructor!==e&&(s=s.split(",")),e.grep(t,function(e){return-1===s.indexOf(e)}).length>0):(s=s.constructor===Array?s:s.split(","),-1===e.inArray(t,s))},n,s)},e.expr[":"]["has-value"]=function(s,r,n){return t(function(s,t){return t.constructor===Array||t.constructor===e?(s.constructor!==Array&&s.constructor!==e&&(s=s.split(",")),e.grep(t,function(e){return-1!==s.indexOf(e)}).length==s.length):e.trim(""+t)==e.trim(""+s)},n,s)},e.expr[":"]["is-equal-to"]=e.expr[":"]["has-value"],e.expr[":"]["not-value"]=function(s,r,n){return t(function(s,t){return t.constructor===Array||t.constructor===e?(s.constructor!==Array&&s.constructor!==e&&(s=s.split(",")),e.grep(t,function(e){return-1!==s.indexOf(e)}).length!=s.length):e.trim(""+t)!=e.trim(""+s)},n,s)},e.expr[":"]["is-not-equal-to"]=e.expr[":"]["has-value"],e.expr[":"]["less-than"]=function(s,r,n){return t(function(s,t){return e.trim(""+t)<s},n,s)},e.expr[":"]["less-than-or-equal-to"]=function(s,r,n){return t(function(s,t){return e.trim(""+t)<=s},n,s)},e.expr[":"]["greater-than"]=function(s,r,n){return t(function(s,t){return e.trim(""+t)>s},n,s)},e.expr[":"]["greater-than-or-equal-to"]=function(s,r,n){return t(function(s,t){return e.trim(""+t)>=s},n,s)},e.expr[":"]["starts-with"]=function(r,n,a){return t(function(t){return 0===e.trim(""+s(e(r))).indexOf(t)},a)},e.expr[":"]["ends-with"]=function(r,n,a){return t(function(t){var n=e.trim(""+s(e(r)));return-1!==n.indexOf(t,n.length-t.length)},a)},e.expr[":"].between=function(r,n,a){return t(function(t){var o=t.split("-");if("undefined"==typeof o[1])return e.expr[":"]["has-value"](r,n,a);var i=e.trim(""+s(e(r)));return i>=o[0]&&i<=o[1]},a)},e.expr[":"]["selected-at-least"]=function(e,s,r){return t(function(e,s){return s.length>=e},r,e)},e.expr[":"]["selected-less-than"]=function(e,s,r){return t(function(e,s){return s.length<=e},r,e)},e.expr[":"].hasOwnProperty("blank")||(e.expr[":"].blank=function(t){var r=s(e(t));return r.constructor===Array||r.constructor===e?r.length<1:!e.trim(""+r)}),e.expr[":"].hasOwnProperty("filled")||(e.expr[":"].filled=function(t){var r=s(e(t));return r.constructor===Array||r.constructor===e?r.length>0:!!e.trim(""+r)}),e.expr[":"].hasOwnProperty("unchecked")||(e.expr[":"].unchecked=function(s){return!e(s).prop("checked")}),e.expr[":"].hasOwnProperty("valid")||(e.expr[":"].valid=function(s){if(!e||!e().parsley)return!0;var t=e(s).data("Parsley");return t?t.isValid():!0}),e.expr[":"].hasOwnProperty("invalid")||(e.expr[":"].invalid=function(s){if(!e||!e().parsley)return!1;var t=e(s).data("Parsley");return t?!t.isValid():!1})}}(window.jQuery||window.Zepto||window.Sprint||{});var mwm=window.mwm||{};mwm.forms=function(e,s){var t=e.forms||{},r=e.alerts||null,n=s("html,body"),a=s(document),o={queue:!1,classes:{applied:"form_applied",processing:"processing",loading:"loading",loadingRedirect:"loading-redirect",ajaxSubmit:"ajax-submit",noAjaxSubmit:"avoid-ajax-submit"},selectors:{ignored:'.ignored, [data-toggle="modal"], .btn-close, button.close',forms:"form",buttons:"button,[type=submit],a.btn",dropdowns:"select:not(.select2-drop,.chzn-done)",fields:"form :input",helpBlocks:".help-block.required, .help-block.validation"},validator:{errorClass:"has-error",successClass:"has-success",errorsWrapper:'<ul class="message-list"></ul>',classHandler:function(e){return e.$element.parents(".form-group").first()},errorsContainer:function(e){return e.$element.parents(".controls").first()}},"password-measure":{selector:".password-measure",classes:{invalid:"invalid",valid:"valid",success:"progress-bar-success",danger:"progress-bar-danger"},options:{}}};return o.selectors.ignoredAndApplied="."+o.classes.applied+", "+o.selectors.ignored,t.submit=t.submit||{},t.submit.submitting=!1,t.watch=t.watch||{},t.apply=t.apply||{},t.handlers=t.handlers||{},t.configure=function(e){o=s.extend(!0,{},o,e)},t.submit.normally=function(e){var n=s(e),a=n.attr("action"),i=t.clicked(n),l="";return i&&i.length&&i.first().hasClass(o.classes.ajaxSubmit)?t.submit.ajax(e):(n.off("submit.normally.forms.mwm"),t.load(n,i),i&&i.length&&(l=i[0].name,s("<input />").attr("type","hidden").attr("name",l).attr("value",i[0].value).appendTo(n)),n.trigger("submits.normally.forms.mwm",[a,l]),r&&r.button(i,function(){n.trigger("submitting.normally.forms.mwm",[a,l]),e.submit()},function(){t.reset(n)})?(n.trigger("submitting.normally.forms.mwm",[a,l]),e.submit(),!0):!r)},t.submit.ajax=function(a){var i=s(a),l=e.ajaxify?e.ajaxify(i.attr("action")):i.attr("action"),d=t.clicked(i),c=i.serializeArray(),u="";if(d&&d.length){if(d.first().hasClass(o.classes.noAjaxSubmit))return t.normally(a);u=d[0].name,c.push({name:u,value:d[0].value})}if(t.load(i,d),i.trigger("submits.ajax.forms.mwm",[l,u]),o.overrides&&o.overrides.ajax)return void o.overrides.ajax.call(t,i,l,u);var m=void 0!==i.data("queue")?i.data("queue"):o.queue,f=s(i.data("targetModal")),p=a.id?s("#"+a.id+"-Alert"):[],h={error:!1};i.data("alertContainer")?p=s(i.data("alertContainer")):d&&d.length&&d.first().data("alertContainer")&&(p=s(d.data("alertContainer"))),f.length&&(h.$modal=f),p.length&&(h.$alert=p),h.$form=i;var w=function(){i.trigger("submitting.ajax.forms.mwm",[l,u]),t.submit.submitting=!0;var a=e.hasOwnProperty("getOpenModals")?e.getOpenModals():[];return s.ajax({type:"POST",queue:m,url:l,data:s.param(c),dataType:"json",success:function(e,d,c){var m=c.getResponseHeader("content-type")||"";if(m.indexOf("html")>-1)return o.overrides&&o.overrides.formReplacement?o.overrides.formReplacement.call(t,i,e,d,c):i.replaceWith(e),i.trigger("mwm::refreshed",[e,d,c]),s.event.trigger("refresh.mwm",[i,e,d,c]),!0;!d||"fail"!=d&&"error"!=d||(h.error=!0);var p=e.data?e.data:e;p.redirect||(p.redirect=i.data("onComplete")),r&&r.message(s.extend({},h,p)),p.redirect||p.reload||p.disabled?t.disable(i):t.reset(i,p.persistent),i.data("closeAfter")&&f.length?setTimeout(function(){f.modal("hide")},1e3*i.data("closeAfter")):p.closeAfter&&a.length&&setTimeout(function(){a.each(function(){s(this).on("hidden.forms.mwm",function(){s(this).off("hidden.forms.mwm")}).modal("hide")})},1e3*p.closeAfter),p.reload&&(n.addClass(o.classes.loading+" "+o.classes.loadingRedirect),location.reload()),i.find(o.selectors.helpBlocks).empty(),i.trigger("successful.ajax.forms.mwm",[l,u,p,{response:e,status:d,xhr:c}])},error:function(e){var n={},a=!1;if(e&&e.responseText){if(e.getResponseHeader("content-type").indexOf("json")>=0){try{n=JSON.parse(e.responseText),n.hasOwnProperty("resetForm")&&(a=n.resetForm)}catch(o){n={message:e.responseText}}n.redirect||(n.redirect=i.data("onError"))}else e&&(n={message:e.responseText});h.success=!0,r&&r.message(s.extend({},h,n))}else r&&r.message({message:"Error. Please try again."});i.trigger("error.ajax.forms.mwm",[l,u,n,{response:n,status:e.statusText,xhr:e}]),a&&t.reset(i)},complete:function(e,s){t.submit.done(i),i.trigger("submitted.ajax.forms.mwm",[l,u,{status:s,xhr:e}])}}),!1};r&&r.button(d,w,function(){forms.reset(i)})?w():r||w()},t.submit.done=function(e){e.removeClass(o.classes.processing),this.submitting=!1},t.watch.submissions=function(){a.off("submit.forms.watch.submissions.mwm").on("submit.forms.watch.submissions.mwm",o.selectors.forms,function(e){var r=s(this),n=r.hasClass(o.classes.ajaxSubmit)?t.submit.ajax(this):t.submit.normally(this);n||(e.preventDefault(),t.submit.submitting||t.reset(r),t.watch.submissions())})},t.watch.resetFieldsOnInvalid=function(){a.off("invalid.fields.watch.forms.mwm").on("invalid.fields.watch.forms.mwm",o.selectors.fields,function(e){var r=s(this).parents(o.selectors.forms).first();r.length&&t.reset(r)})},t.watch.setButtonClicked=function(){a.off("click.buttons.watch.forms.mwm touchstart.buttons.watch.forms.mwm").on("click.buttons.watch.forms.mwm touchstart.buttons.watch.forms.mwm",o.selectors.buttons,function(e){s(this).data("clicked",s(e.target))})},t.apply.buttons=function(e){if(s().button){var t;t=e?s(o.selectors.buttons,e):s(o.selectors.buttons,o.selectors.forms),t.not(o.selectors.ignoredAndApplied).button().addClass(o.classes.applied)}},t.apply.chosen=function(){s().chosen&&s(o.selectors.dropdowns,o.selectors.forms).not(o.selectors.ignoredAndApplied).each(function(){if(o.overrides&&o.overrides.applyChosen)return o.overrides.applyChosen.call(this,t),!0;var e=s(this),r=s.extend({disable_search_threshold:10},e.data()),n=e.children().filter(function(){return!this.value||0===s.trim(this.value).length});n.length&&(e.data("placeholder")||e.data("placeholder",n.text()),r.allow_single_deselect=!0,n.empty()),e.chosen(r).addClass(forms.applied)})},t.apply.select2=function(){s().select2&&s(o.selectors.dropdowns,o.selectors.forms).not(o.selectors.ignoredAndApplied).select2().addClass(o.classes.applied)},t.apply.labels=function(){s().flyLabels&&s(o.selectors.forms).not(o.selectors.ignoredAndApplied).addClass(o.classes.applied).flyLabels()},t.apply.validation=function(){if(s().parsley){var e=s(o.selectors.forms).not(o.selectors.ignoredAndApplied).addClass(o.classes.applied);e.length&&e.parsley(o.validator)}},t.apply.passwords=function(){s().complexify&&s(o["password-measure"].selector).not(o.selectors.ignoredAndApplied).addClass(o.classes.applied).each(function(){var e=s(this),t=s("#"+this.id+"-Guide"),r=e.data("rulePassword")?e.data("rulePassword"):e.data("parsleyPassword");e.data("passwordGuide")&&(t=s(e.data("passwordGuide")));var n=t.find(".password-guide--bar");e.off("reset.passwords.forms.mwm").on("reset.passwords.forms.mwm",function(){s(this).keyup()}).complexify(s.extend(!0,{},{strengthScaleFactor:.35},o["password-measure"].options,r),function(a,i){var l=s(this).val();a&&window.ParsleyConfig&&window.ParsleyConfig.validators&&window.ParsleyConfig.validators.password&&window.ParsleyConfig.validators.password.fn&&(r.skipComplexify=!0,a=window.ParsleyConfig.validators.password.fn(l,r)),a?(t.removeClass(o["password-measure"].classes.invalid).addClass(o["password-measure"].classes.valid),n.removeClass(o["password-measure"].classes.danger).addClass(o["password-measure"].classes.success)):(t.removeClass(o["password-measure"].classes.valid).addClass(o["password-measure"].classes.invalid),n.removeClass(o["password-measure"].classes.success).addClass(o["password-measure"].classes.danger)),n.width(i+"%"),e.trigger("mwm::password-check",[a,i,{options:r,$guide:t,$bar:n}])})})},t.clicked=function(e){var t=e.find(o.selectors.buttons),r=null;return t.length&&(t.each(function(){return s(this).data("clicked")?(r=s(this),!1):void 0}),r||(r=t.filter(".btn-defaulted").first()),r&&r.length||(r=t.first())),r},t.load=function(e,r){r||(r=t.clicked(e)),e.addClass(o.classes.processing),r?(s().button&&r.hasClass(o.classes.applied)?r.button("loading"):r.addClass(o.classes.loading),e.find(o.selectors.buttons).each(function(){this!==r[0]&&s(this).addClass(o.classes.loading)})):(s().button&&e.find(o.selectors.buttons).filter("."+o.classes.applied).not(o.selectors.ignored).button("loading"),e.find(o.selectors.buttons).not(o.selectors.ignoredAndApplied).addClass(o.classes.loading))},t.reset=function(e,t){e.removeClass(o.classes.processing),t||e[0].reset(),s().button&&e.find(o.selectors.buttons).filter("."+o.classes.applied).not(o.selectors.ignored).button("reset"),e.find(o.selectors.buttons).not(o.selectors.ignoredAndApplied).removeClass(o.classes.loading),e.find(o.selectors.buttons).each(function(){this.disabled=!1;var e=s(this);e.data("clicked",null),e.data("tooltip")&&e.tooltip("hide")})},t.disable=function(e){e.find(o.selectors.buttons).each(function(){s(this).is(o.selectors.ignored)||(this.disabled=!0)})},t.enable=function(e){e.find(o.selectors.buttons).each(function(){s(this).is(o.selectors.ignored)||(this.disabled=!1)})},t.ready=function(){this.apply.buttons(),this.apply.chosen(),this.apply.labels(),this.apply.validation(),this.apply.passwords()},t.init=function(){this.watch.submissions(),this.watch.resetFieldsOnInvalid(),this.watch.setButtonClicked()},t.hasOwnProperty("manual")&&t.hasOwnProperty("manual")||(t.init(),a.ready(function(){t.ready()}).on("refresh.mwm",function(){t.ready()})),t}(window.mwm||{},window.jQuery||window.Zepto||window.Sprint);var mwm=window.mwm||{};mwm.conditionals=function(e,s){var t=e.conditionals||{},r={selector:"[data-hide-if],[data-show-if]",toggleClass:"hide",animated:"filled"};return t.configure=function(e){r=s.extend(!0,{},r,e)},t.ready=function(){s(r.selector).each(function(){var e=s(this),t=e.data("hideIf"),n=void 0===t?[]:t.split(","),a=e.data("showIf"),o=void 0===a?[]:a.split(","),i=[];s.each(n,function(t,n){var a=n.split(":"),o=a[0],l=s(o),d=a[1];return l.length?(-1===s.inArray(o,i)&&(l.off("keyup.conditionals.watch.forms.mwm change.conditionals.watch.forms.mwm"),i.push(o)),l.off("keyup.conditionals.watch.forms.mwm change.conditionals.watch.forms.mwm").on("keyup.conditionals.watch.forms.mwm change.conditionals.watch.forms.mwm",function(){var t=s(this);t.is(":"+d)?(e.data("hideClass")?e.addClass(e.data("hideClass")):e.addClass(r.toggleClass),e.removeClass(r.animated),e.trigger("hidden.conditionals.mwm",[t,d])):(e.data("hideClass")?e.removeClass(e.data("hideClass")):e.removeClass(r.toggleClass),e.addClass(r.animated),e.trigger("shown.conditionals.mwm",[t,d]))}).trigger("change"),!0):!0}),s.each(o,function(t,n){var a=n.split(":"),o=a[0],l=s(o),d=a[1];return l.length?(-1===s.inArray(o,i)&&(l.off("keyup.conditionals.watch.forms.mwm change.conditionals.watch.forms.mwm"),i.push(o)),l.off("keyup.conditionals.watch.forms.mwm change.conditionals.watch.forms.mwm").on("keyup.conditionals.watch.forms.mwm change.conditionals.watch.forms.mwm",function(){var t=s(this);t.is(":"+d)?(e.data("hideClass")?e.removeClass(e.data("hideClass")):e.removeClass(r.toggleClass),e.addClass(r.animated),e.trigger("shown.conditionals.mwm",[t,d])):(e.data("hideClass")?e.addClass(e.data("hideClass")):e.addClass(r.toggleClass),e.removeClass(r.animated),e.trigger("hidden.conditionals.watch.forms.mwm",[t,d]))}).trigger("change"),!0):!0})})},t.hasOwnProperty("manual")&&t.hasOwnProperty("manual")||s(document).ready(function(){t.ready()}),t}(window.mwm||{},window.jQuery||window.Zepto||window.Sprint);