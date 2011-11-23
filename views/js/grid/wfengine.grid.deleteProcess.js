/**
 * This class is a grid column adapter used to format cells to fit specific needs
 * Adapt cell to delete processes

 * @see TaoGridClass
 * 
 * @require jquery >= 1.3.2 [http://jquery.com/]
 * 
 * @author Alfonsi Cédric, <taosupport@tudor.lu>
 */

/**
 * The TaoGridDeleteProcessAdapter constructor
 */

function TaoGridDeleteProcessAdapter(){}

TaoGridDeleteProcessAdapter.preFormatter = function(grid, rowData, rowId, columnId)
{
	var returnValue = rowId;
	return returnValue;
}

TaoGridDeleteProcessAdapter.formatter = function(cellvalue, options, rowObject)
{
	var returnValue = '<a href="#"><img src="/tao/views/img/delete.png"/></a>';
	return returnValue;
}

TaoGridDeleteProcessAdapter.postCellFormat = function(grid, cell, rowId, columnId)
{
	var processExecutionUri = grid.data[rowId][columnId];
	$(cell).find('a').one('click', function(){
		wfApi.ProcessExecution.delete(processExecutionUri, function(data){
			console.log('delete the process execution '+processExecutionUri);
		}, function(){
			console.log('unable to delete the process execution '+processExecutionUri);
		});
	});
}