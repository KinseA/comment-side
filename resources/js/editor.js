function id(elem){
	return document.getElementById(elem);
}

function prepareEditor(){
	id('editor').innerHTML='';
	id('editor').appendChild(document.createElement('p'));
}
