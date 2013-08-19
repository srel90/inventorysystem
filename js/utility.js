// JavaScript Document
function ajax(url,data,callback){
	var response=$.ajax({
        url: url,
        type: 'POST',
        data: data,
	async: false
    }).responseText;
	if(callback)alert(response);
	return response;
}
function pad(a,b){return(1e15+a+"").slice(-b)}
function getRDOValue(radioName){   
    var rdo = document.getElementsByName(radioName);
    for(i=0;i < rdo.length;i++)
    {   
        if(rdo[i].checked) {return rdo[i].value;}
    }
    return null;
}
function setRDOValue(radioName,radioValue){  
    var rdo = document.getElementsByName(radioName);
    for(i=0;i < rdo.length;i++)
    {   
        if(rdo[i].value==radioValue)
		{
		rdo[i].checked = true;
		break;
		}
    }
    return null;
}
function chkIDCard(value)
{
if(value.length != 13) return false;
for(i=0, sum=0; i < 12; i++)
sum += parseFloat(value.charAt(i))*(13-i); if((11-sum%11)%10!=parseFloat(value.charAt(12)))
return false; return true;
}
function _Redirect (url) {

    // IE8 and lower fix
    if (navigator.userAgent.match(/MSIE\s(?!9.0)/)) {
        var referLink = document.createElement('a');
        referLink.href = url;
        document.body.appendChild(referLink);
        referLink.click();
    } 

    // All other browsers
    else { window.location.href = url; }
}
function arrayToCSV(arr) {
    var columnNames = [];
    var rows = [];
    for (var i=0, len=arr.length; i<len; i++) {
        // Each obj represents a row in the table
        var obj = arr[i];
        // row will collect data from obj
        var row = [];
        for (var key in obj) {
            // Don't iterate through prototype stuff
            if (!obj.hasOwnProperty(key)) continue;
            // Collect the column names only once
            if (i === 0) columnNames.push(prepareValueForCSV(key));
            // Collect the data
            row.push(prepareValueForCSV(obj[key]));
        }
        // Push each row to the main collection as csv string
        rows.push(row.join(','));
    }
    // Put the columnNames at the beginning of all the rows
    rows.unshift(columnNames.join(','));
    // Return the csv string
    return rows.join('\n');
}

// This function allows us to have commas, line breaks, and double 
// quotes in our value without breaking CSV format.
function prepareValueForCSV(val) {
    val = '' + val;
    // Escape quotes to avoid ending the value prematurely.
    val = val.replace(/"/g, '""');
    return '"' + val + '"';
}
function printGrid(grid) {
    var gridElement = grid,
        win = window.open('', '', 'width=800, height=500'),
        doc = win.document.open(),
        htmlStart = 
            '<!DOCTYPE html>' +
            '<html>' +
            '<head>' +
            '<meta charset="utf-8" />' +
            '<title>Report</title>' +
            '<link href="http://cdn.kendostatic.com/' + kendo.version + '/styles/kendo.common.min.css" rel="stylesheet" /> '+
            '<style>' +
            'html { font: 11pt sans-serif; }' +
            '.k-grid, .k-grid-content { height: auto !important; }' +
            '.k-grid-toolbar, .k-grid-pager > .k-link { display: none; }' +
            '.k-pager-sizes, .k-grouping-header, .k-toolbar,.k-grid-pager {display: none;}'+
            '</style>' +
            '</head>' +
            '<body>',
        htmlEnd = 
            '</body>' +
            '</html>';

    doc.write(htmlStart + gridElement.clone()[0].outerHTML + htmlEnd);
    doc.close();
    win.print();
}

