
alert("this javascript was loaded with js-example.php plugin");
alert("this is a single value that was added" + js_example_params_single);

var text = '';
// you can manipulate an object that you passed in from wordpress
for(var i =0; i< js_example_params_object.length; i++){
   text = text + js_example_params_object[i];
}

alert("text value" + text);    
