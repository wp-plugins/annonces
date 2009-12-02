/*		FILL A INPUT WITH ANOTHER FIELDS 		*/
function add_column_to_export_strucure(idtofill , stringtoadd, text_separator)
{
	var input_to_fill = document.getElementById(idtofill);
	var checkbox = document.getElementById(stringtoadd);
	var box_is_checked = checkbox.checked;
	if(box_is_checked)
	{
		input_to_fill.value += text_separator + checkbox.value + text_separator + ",";
	}
	else
	{
		input_to_fill.value = input_to_fill.value.replace(text_separator + checkbox.value + text_separator + ",",'');
	}
}

/*		EMPTY A INPUT												*/
function empty_input_field(idtofill)
{
	var input_to_fill = document.getElementById(idtofill);
	input_to_fill.value = '';
}

var id = 0;
function AddSubElement_frame(the_src, form, wheretoadd, actualnumber, maxnumber, the_height)
{	
	id++;
	form.enctype="multipart/form-data";

	var frame = document.createElement("iframe");
	frame.id = id;
	frame.src = the_src;
	//frame.height = (the_height + 50) + 'px';
	frame.style.overflow = 'noscroll';

	document.getElementById(wheretoadd).appendChild(frame);
}
 
function check_selection(form,action){
	for (i=0, n=form.elements.length; i<n; i++)
	{
    if (action == 'check_all') form.elements[i].checked = true;
		else if (action == 'uncheck_all') form.elements[i].checked = false;
	}
}