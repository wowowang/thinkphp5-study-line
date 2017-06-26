function findFatherNode(){
	
		var uls = document.getElementsByTagName("ul");
		var ret = [];
		for(var i = 0; i < uls.length; i++){
			if(uls[i].hasChildNodes() && uls[i].id!= "outer_wrap"){
			   var node = uls[i].parentNode;
			   //alert("fatherNode  "+ node.nodeName);
			   ret.push(node);
			}
		}
		return ret;
	}
	
	function next(el){
		while(el.nextSibling){
			if(el.nextSibling.nodeType == 1){   //同层下一个兄弟节点当节点类型为"元素"时
				return el.nextSibling;
			}
			el = el.nextSibling; 
		}
		return null;
	}
	var innerText = document.innerText ? 'innerText' : 'textContent';
	var span = document.createElement('span');
	span[innerText] = '-';
	span.className = 'controlSymbol';
	var outerWrap = document.getElementById('outer_wrap');
	var fs = findFatherNode();
		
	for(var i = 0 ; i < fs.length; i++){
		 var innerSpan = span.cloneNode(true);
		 fs[i].insertBefore(innerSpan,fs[i].firstChild);
	  }
	  var outerWrap = document.getElementById('outer_wrap');
	
	var i=0;
	var j=1;
	
	outerWrap.onclick = function(event){
		event = event || window.event;
		var t = event.target || event.srcElement;
		
		if(t.className == 'controlSymbol'){
			var sn = t.parentNode.childNodes[5];
			var snStyle = sn.style;
			snStyle.display = (snStyle.display == 'block' || snStyle.display == '') ? 'none' : 'block';
			t[innerText] = (t[innerText] == '+' ? '-':'+');
	      }else if(t.nodeName == 'SPAN' ){
			  if(i<10){
				  i++;  
			   }else{
				   j++;
			       i=1;
			   }
	            var div = document.getElementById("right");
				
				var div_pic = document.createElement("pic");
				div_pic.setAttribute("class","pic");
				div_pic.style.marginLeft=10+"px";
				div.appendChild(div_pic);
				
			    var img = document.createElement("div");
			    img.setAttribute("class","newImg");
				/*
			    img.style.marginLeft=(80*(i)-40)+'px';
			    img.style.marginTop=(50*j-40)+'px';
				*/
			    div_pic.appendChild(img);
			    var textSpan = document.createElement("span");
			    textSpan.innerHTML = t.innerHTML;
			    textSpan.setAttribute("class","label");
				/*
				textSpan.style.marginLeft=(80*(i)-40)+'px';
			    textSpan.style.top=(50*j-10)+'px';
				*/
			    div_pic.appendChild(textSpan);
				div.appendChild(div_pic);
		  }		  
	}
	
	var right = document.getElementById("right");
	right.onclick = function(event){
		
		event = event || window.event;
		var t = event.target || event.srcElement;
		/*
		if(t.className == 'label'){
			t.style.display = "none";
			t.previousSibling.style.display="none";
			var next = t.nextSibling;
			while(next!=null){
				//之后的视频缩略图和下面的标题通通前移
				if(next.className == 'newImg'){
					if(next.styleleft.equals("40px")){
						next.style.left=680+"px";
						next.style.top=10+"px";
					}
					var left = (next.style.left.split("px")[0]);
					next.style.left = (left-80)+"px";
				}else{
					var left = (next.style.left.split("px")[0]);
					next.style.left = (left-80) +"px";
				}
				next = next.nextSibling;
			}
		}
		*/
		if(t.className == 'pic' || t.className == 'label'){
			if(t.className == 'label'){
				//alert(t.parentNode);
				t.parentNode.style.display = "none";
			}
			t.style.display = "none";
			//alert(t.style.display);
		}
		
	}