/**
 * Tabella - Dibi DataGrid for Nette
 *
 * This source file is subject to the "New BSD License".
 *
 * @author     Vojtěch Knyttl
 * @copyright  Copyright (c) 2010 Vojtěch Knyttl
 * @license    New BSD License
 * @link       http://tabella.knyt.tl/
 */

$(document).ready(function() {
	if ($(".tabella").length == 0)
		return false;

	$(".tabella").each(function(key, val) {
		eval('var foo = ' + $(val).attr("data-params"));
		tabella.params[$(val).attr("data-id")] = foo;
	});

	$(".tabella .dateFilter").tabellaDatePicker();

	$(".tabella_ajax").live("click", function(event) {
		$(this).tabellaFadeBody();
		tabella.getContents(this.href);
		event.preventDefault();
		return false;
	});

	$(".tabella .filter").live("change", function() {
		focused = $(this).attr("data-id");
		name = $(this).tabellaEl().attr("data-id");

		filters = "?do="+name+"-reset&";
		$(this).tabellaFadeBody();

		$(".tabella .filter").each(function() {
			filters += name+"-filter["+$(this).attr("name")+"]="+encodeURIComponent($(this).val())+"&";
		});

		tabella.getContents(window.location.pathname+filters, function(payload) {
			$("div[name='"+name+"']").find("input[name='"+focused+"']").focus();
		});
	});

	$(document).keydown(function(event) {
		if (event.keyCode == 13) {
			$(".edited .save").click();
		}
		if (event.keyCode == 27) {
			$(".edited .cancel").click();
		}
	});

	// bindings for inline editing
	$(".tabella .editable").live("click", function() {
		// starting the edition
		row = $(this).parents("tr");
		if (!row.hasClass("edited")) {
			row.tabellaFinishEdit();
			row.tabellaStartEdit();
			$(this).find("input, select, textarea").focus().click().click();
		}
	});

	$(".tabella .button").live("click", function() {
		row = $(this).parents("tr");
		if ($(this).hasClass("save")) {
			row.tabellaFade();
			var data = "";

			// tabella name
			var name = $(this).tabellaName();

			// creating the request
			var payload = new Object();

			row.find("input, textarea, select").each(function() {
				key = name+"-"+$(this).attr("name");
				payload[key] = $(this).val();
			});
			// saving the inline edit

			$.post($(this).tabellaEl().attr('data-submit-url'), payload, tabella.ajaxSuccess);
		} else {
			if (row.attr("data-id") == 0) {
				row.tabellaEl().find(".delete").show();
				row.remove();
			}
		}
		// removing the inline edit elements
		row.tabellaFinishEdit();
	});


	$(".tabella .delete").live({
		mouseenter: function() {
			$(this).parent().css("opacity", "0.5");
		},
		mouseleave: function() {
			$(this).parent().css("opacity", "1");
		},
		click: function() {
			tr = $(this).parents("tr");
			tr.tabellaFade();
			if (!confirm("Sure to delete?")) {
				tr.css("opacity", "1");
				return;
			}
			$.post($(this).tabellaEl().attr('data-submit-url'),
				$(this).tabellaName()+'-deleteId='+tr.attr("data-id"), tabella.ajaxSuccess);
		}
	});

	$(".tabella .add").live("click", function() {
		tr = $("<tr data-id=0>");
		tabellaParams = tabella.params[$(this).tabellaName()];
		$.each(tabellaParams["cols"], function(key, val) {
			td = $("<td>");

			params = val["params"];

			td.attr("data-format", params[params['type']+"Format"]);
			td.css("width", params['width']+"px");
			if (params["type"] == "delete")
				return;
			$.each(params["class"], function(key, cl) {
				td.addClass(cl);
			});
			if (params["editable"])
				td.addClass("editable");
			td.attr("data-type", params["type"])
				.attr("data-name", val["colName"]);

			tr.append(td);
		});
		tr.prependTo($(this).tabellaEl().find(".tabella-body"));
		tr.tabellaStartEdit();
	});
});

tabella = {
	getContents: function(url, success) {

		if (typeof history.replaceState != 'undefined')
			history.replaceState("", "", url);
		tabella.currentAjax++;
		$.getJSON(url, {}, function(payload) {
			if (tabella.currentAjax == 1) {
				tabella.ajaxSuccess(payload);
				if (success)
					success(payload);
			}
			tabella.currentAjax--;
		});
	},



	ajaxSuccess: function(payload) {
		// partially based on Nette ajax script by David Grudl and Jan Marek
		if (payload.snippets) {
			for (var i in payload.snippets) {
				$("#" + i).html(payload.snippets[i]);
			}
		}
	},
	currentAjax: 0,
	params: []
};


	$.fn.extend({
		tabellaName: function() {
			return $(this).tabellaEl().attr("data-id");
		},
		tabellaEl: function() {
			return $(this).parents(".tabella");
		},
		tabellaFadeBody: function() {
			$(this).tabellaEl().find(".tabella-body").tabellaFade();
		},
		tabellaFade: function() {
			$(this).css("opacity", "0.5");
		},
		tabellaStartEdit: function() {
			var row = $(this);
			$(this).addClass("edited");
			$(this).tabellaEl().find(".delete").hide();
			$(this).find(".editable").each(function() {
				var cell;
				var name = $(this).attr("data-name");
				var val = $(this).tabellaEl().find(".filter[name='"+name+"']").val();
				switch($(this).attr("data-type")) {
					case "text":
						cell = $("<input type=text>");
						break;
					case "textarea":
							cell = $("<textarea>");
							break;
					case "checkbox":
						cell = $("<input type=checkbox>")
							.attr("name", name)
							.attr("checked", $(this).attr("data-editable") == "1");
						$(this).html(cell);
						cell = null;
						break;
					case "date":
						cell = $("<input type=text>")
							.attr("name", name)
							.val($(this).text());
						$(this).html(cell);
						cell.css("width", ($(this).css("width").match(/\d+/)[0]*1+4)+"px !important");
						cell.tabellaDatePicker();
						cell = null;
						break;
					case "select":
						cell = $("<select>");

						$.each(tabella.params[$(this).tabellaName()]["cols"][name]["params"]["options"],
							function(key, val) {
								cell.append($("<option>").attr("value",key).html(val));
							});
						break;
				}
				if (cell) {
					cell.attr("name", name)
						.val($(this).attr("data-editable"));
					$(this).html(cell);
					cell.css("width", ($(this).css("width").match(/\d+/)[0]*1+4)+"px !important");
					if(row.attr("data-id") == 0)
						cell.val(val);
				}
			});
			$(this).find(".editable:first").append($("<input name=id type=hidden>").attr("value", $(this).attr("data-id")));
			$(this).append('<td class="button save"></td><td class="button cancel"></td>');
		},



		tabellaFinishEdit: function() {
			$(this).tabellaEl().find(".delete").show();
			$(this).parent().find(".edited").each(function() {
				$(this).removeClass("edited");
				$(this).find(".button").remove();
				$(this).find(".editable").each(function() {
					$(this).html($(this).attr("data-shown"));
				});
			});
		},



		// function to run the date picker tool
		tabellaDatePicker: function() {
			if (this.length == 0)
				return
			format = $(this).parent().attr("data-format");
		}
	});
