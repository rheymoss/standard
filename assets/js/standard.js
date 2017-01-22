/** Standard JS for Standard CSS **/
/** Godfrey Musa **/


var _s = function (selector) {
  return document.querySelector(selector);
};
if(document.getElementById("standard-modal") !== null){
  document.getElementById("standard-modal").addEventListener("click", function(){
    standard.pop.down();
  });
}

var standard = {

    init : function(){
      this.css();
      console.log('StandardJS Initialized');
    }, // init
    css : function(){

      var percent = 'st-cent';
      var bgcolor = 'st-bg';
      var column = 'st-col'
      var bgImg = 'st-bgimg'

      var elements = document.querySelectorAll('[' + 'st' + ']');
      for (var i = 0; i < elements.length; i++)
      { // loop start

        var element = elements[i];
        if (element.column !== 'undefined')
        {
          var elValue = elements[i].getAttribute(column);
          element.className += ' c' + elValue + 'cent';
        }

        if (element.bgcolor !== 'undefined')
        {
          var elValue = elements[i].getAttribute(bgcolor);
          element.style.backgroundColor = elValue;
        }

        if (element.percent !== 'undefined')
        {
          var elValue = elements[i].getAttribute(percent);
          element.style.width = elValue + '%';
        }

        if (element.bgImg !== 'undefined')
        {
          console.log('Yes');
          var elValue = elements[i].getAttribute(bgImg);
          element.style.backgroundImage = 'url(' + elValue + ')';
        }


      } // loop end

    }, // css
    pop : { // Pop Up Element
      up : function(element, callback)
      {
        var eleModal = document.getElementById(element);
          if(document.getElementById('standard-modal') === null)
          {
            var bgDiv = document.createElement('div');
            bgDiv.id += 'standard-modal';
            document.getElementsByTagName("body")[0].appendChild(bgDiv);
          }else{
            document.getElementById('standard-modal').style.display = 'block';
          }
          if(eleModal !== null){
            if(eleModal.classList.contains('modal') === false)
            {
              eleModal.className += ' modal';
              eleModal.style.display = 'block';
            }else{
              eleModal.style.display = 'block';
            }
          }
        document.getElementsByTagName("body")[0].style.overflow = 'hidden';

      },
      actDown : function (element){
          var eleModal = document.getElementById(element);
          if(document.getElementById('standard-modal') === null)
          {
            var bgDiv = document.createElement('div');
            bgDiv.id += 'standard-modal';
            document.getElementsByTagName("body")[0].appendChild(bgDiv);
          }else{
            document.getElementById('standard-modal').style.display = 'none';
          }
          if(eleModal !== null){
            if(eleModal.classList.contains('modal') === false)
            {
              eleModal.className += ' modal ';
              eleModal.style.display = 'none';
            }else{
              eleModal.style.display = 'none';
            }
          }
          document.getElementsByTagName("body")[0].style.overflow = '';
      },
      down : function(element, callback){

        if(element === undefined){
          var modal = document.getElementsByClassName('modal')[0];
          if(modal === undefined){

          }else{
            var element = modal.id;
            standard.pop.actDown(element)
          }
        }else{
          standard.pop.actDown(element);
        }

      },
      toggle : function(eleModal, callback){
        var element = document.getElementById(eleModal);
        console.log(element.style.display);
        if(element.style.display == 'none' || element.style.display == '')
        {
          this.up(eleModal)
        }else{
          this.down(eleModal);
        }
      },

    }, // set element
    set : {
      macro : function (data){
        if (typeof(data) == 'object'){
        var str = document.body.innerText;
        var result = getMacro.get(str,"{{","}}");
        console.log(result);

        for (re in result)
        {
          for (var k in data)
          {
            var key = '{{'+k+'}}';
            var value = data[k];

            replaceTextOnPage(key, value);

          }

        }

        }

      },
      to : function(data){

        for (var k in data)
        {

          var key = 'st-'+k+'';
          var value = data[k];

          if(document.querySelectorAll('[' + 'st-'+ k + ']').length)
          {
            var eletarget = document.querySelectorAll('['+key+']');
            eletarget.key = value;
            console.log('Changing value of '+key+ ' to '+ data[k]);

          }

        }

      },
      value : function(data){
        for (var k in data)
        {

          var key = 'st-'+k+'';
          var value = data[k];

          if(document.querySelectorAll('[' + 'st-'+ k + ']') !== null)
          {
            var eletarget = document.querySelectorAll('['+key+']');
            eletarget.value = value;
            eletarget.nodeValue = value;
            console.log('Changing value of '+key+ ' to '+ data[k]);

          }

        }

      }
    }
  }

//(function(){
//
// standard.init()
//
//})();

var getMacro = {
    results:[],
    string:"",
    getMacro:function (sub1,sub2) {
        if(this.string.indexOf(sub1) < 0 || this.string.indexOf(sub2) < 0) return false;
        var SP = this.string.indexOf(sub1)+sub1.length;
        var string1 = this.string.substr(0,SP);
        var string2 = this.string.substr(SP);
        var TP = string1.length + string2.indexOf(sub2);
        return this.string.substring(SP,TP);
    },
    removeFromBetween:function (sub1,sub2) {
        if(this.string.indexOf(sub1) < 0 || this.string.indexOf(sub2) < 0) return false;
        var removal = sub1+this.getMacro(sub1,sub2)+sub2;
        this.string = this.string.replace(removal,"");
    },
    getAllResults:function (sub1,sub2) {
        // first check to see if we do have both substrings
        if(this.string.indexOf(sub1) < 0 || this.string.indexOf(sub2) < 0) return;

        // find one result
        var result = this.getMacro(sub1,sub2);
        // push it to the results array
        this.results.push(result);
        // remove the most recently found one from the string
        this.removeFromBetween(sub1,sub2);

        // if there's more substrings
        if(this.string.indexOf(sub1) > -1 && this.string.indexOf(sub2) > -1) {
            this.getAllResults(sub1,sub2);
        }
        else return;
    },
    get:function (string,sub1,sub2) {
        this.results = [];
        this.string = string;
        this.getAllResults(sub1,sub2);
        return this.results;
    }
};


function replaceTextOnPage(from, to){
  getAllTextNodes().forEach(function(node){
    node.nodeValue = node.nodeValue.replace(new RegExp(quote(from), 'g'), to);
  });
  function getAllTextNodes(){
    var result = [];
    (function scanSubTree(node){
    if(node.childNodes.length)
      for(var i = 0; i < node.childNodes.length; i++)
        scanSubTree(node.childNodes[i]);
    else if(node.nodeType == Node.TEXT_NODE)
      result.push(node);
    })(document);
      return result;
    }
  function quote(str){
    return (str+'').replace(/([.?*+^$[\]\\(){}|-])/g, "\\$1");
  }
}

window.onload = function() {
  standard.init();
}
