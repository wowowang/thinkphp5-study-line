// JavaScript Document
/* var json = [{"desc":"myVideo1","pid":-1,"id":1,"playpath":"http:\/\/115.28.91.201\/videoedit\/video\/myVideo1.mp4","thumbnailpath":"http:\/\/115.28.91.201\/videoedit\/image\/1.jpg"},{"desc":"myVideo2","pid":-1,"id":2,"playpath":"http:\/\/115.28.91.201\/videoedit\/video\/myVideo2.mp4","thumbnailpath":"http:\/\/115.28.91.201\/videoedit\/image\/2.jpg"},{"desc":"\u7b2c\u4e09\u4e2a\u89c6\u9891","pid":-1,"id":3,"playpath":"http:\/\/115.28.91.201\/videoedit\/video\/myVideo3.mp4","thumbnailpath":"http:\/\/115.28.91.201\/videoedit\/image\/3.jpg"},{"desc":"\u526a\u5207\u89c6\u9891","pid":1,"id":266,"playpath":"http:\/\/115.28.91.201\/videoedit\/video\/user5\/1.mp4-1437552209004.mp4","thumbnailpath":"http:\/\/115.28.91.201\/videoedit\/video\/user5\/1.mp4-1437552209004.mp4-snap.jpg"},{"desc":"2\u526a\u5207\u89c6\u9891","pid":2,"id":267,"playpath":"http:\/\/115.28.91.201\/videoedit\/video\/user5\/2.mp4-1437552473829.mp4","thumbnailpath":"http:\/\/115.28.91.201\/videoedit\/video\/user5\/2.mp4-1437552473829.mp4-snap.jpg"}];
*/ 
//var info = eval(json);
var innerText = document.innerText ? 'innerText' : 'textContent';
var span = document.createElement('span');
span[innerText] = '-';
span.className = 'controlSymbol';
var outerWrap = document.getElementById('outer_wrap');
var myVideo, volumeBar, volumeNumbers, volumeMouse = false, mouseTimer,
 seekBar, seekNumbers, playButton, muteButton;
//ok 0   alert("called 0");	
	outerWrap.onclick = function(event){
		event = event || window.event;
		var t = event.target || event.srcElement;
		
		if(t.className == 'controlSymbol'){
			
			var sn = t.parentNode.childNodes[3];
	//sss		alert("sn   "+ sn.nodeName );
			
			var snStyle = sn.style;
			snStyle.display = (snStyle.display == 'block' || snStyle.display == '') ? 'none' : 'block';
			t[innerText] = (t[innerText] == '+' ? '-':'+');
			
			
	      }else if(t.nodeName == 'A' ){
		      //  alert("add to concat list"+t.nodeName);
	            var div = document.getElementsByClassName("right_in")[0];
				
				var div_pic = document.createElement("div");
				div_pic.setAttribute("class","pic");
				div_pic.setAttribute("id", t.innerHTML);
				div_pic.style.marginLeft=10+"px";
				div_pic.style.marginTop=10+"px";
				
				var img = document.createElement("div");
			    img.setAttribute("class","newImg");
				var i;
				if(t.parentNode.childNodes[0].nodeName == "IMG"){
                    
					i = t.parentNode.childNodes[0];
				}else{
					i = t.parentNode.childNodes[1];
				}
				var p = document.createElement("img");
				//img.style.backgroundImage ="url("+i.src+")";
				p.src = i.src;
				img.appendChild(p);
				div_pic.appendChild(img);
				
			    var checkBox = document.createElement("input");
				checkBox.type = "checkbox";
				checkBox.name = "check";
				checkBox.vid= t.innerHTML;
				checkBox.className =  t.innerHTML;
	//sss			alert("class   "+ checkBox.className);
		//sss		alert("add to check class   "+ checkBox.vid);
				div_pic.appendChild(checkBox);
				
			    var textSpan = document.createElement("span");
			    textSpan.innerHTML = t.innerHTML;
			    textSpan.setAttribute("class","label");
				div_pic.appendChild(textSpan);
				div.appendChild(div_pic);
		  }		  
	}
	
	var right = document.getElementsByClassName("right_in")[0];
	right.onclick = function(event){
		
		event = event || window.event;
		var t = event.target || event.srcElement;
	
//sss		alert(t.className);
		if(t.className == 'pic' ){
			 //点击横框中的视频，则会在上面的视频框中开始播放，每个div的id是数据库中视频的ID
			 var str = t.id;
			 var arr = str.split(",");
//sss			 alert("分割数组：  "+ arr[1]);
//sss last			 alert(info[arr[1]]);
			 openVideo(info[arr[1]].playpath,t.id);
		}
		
	}
	
	//全选按钮的操作函数
	function selectAll(){
		var cb = document.createElement("input");
		var selectBoxs = document.getElementsByName("check");
		for(var i=0;i<selectBoxs.length;i++){
			selectBoxs[i].checked = true;
		//	select:Boxs[i].name="selected";
		}
	}
	
	function deleteSelect(){
		var cb = document.createElement("input");
		var selectBoxs = document.getElementsByName("check");
		//在数组中存储当前要删除的视频id
		//alert("select count:" + selectBoxs.length);
		var selectedLen = selectBoxs.length;
		for(var i=selectedLen-1;i>=0;i--){
			//alert("i:"+i);
			if(selectBoxs[i].checked) {
			//	selectBoxs[i].parentNode.style.display = "none";
			//}
				//selectBoxs[i].checked =false;
			///	alert("select count:" + selectBoxs.length);
				selectBoxs[i].parentNode.parentNode.removeChild(selectBoxs[i].parentNode);
			}
		}

		
		
	}
	function addSelect(){
		var cb = document.createElement("input");
		var selectBoxs = document.getElementsByName("check");
		var ids = [];
		//在数组中存储当前要合并的视频id
		for(var i=0;i<selectBoxs.length;i++){
			if(selectBoxs[i].checked) {
				//alert(selectBoxs[i].className);
				ids.push(selectBoxs[i].className);
			}
		}
	}
	 function openVideo(path,id){
  	  var video=$('#mdabdvid');
      var video_source=$('#videosource');
      video_source[0].src=path;
      video[0].load();
      video[0].play();
      old_name=id;
    //sss  alert(id);
  }
  function volume_up() {
   var vol = myVideo.volume + 0.01;
   myVideo.volume = Math.min(vol, 1);
   if(volumeMouse) {
      mouseTimer = setTimeout(volume_up, 50);
   }
}
 
function volume_down() {
   var vol = myVideo.volume - 0.01;
   myVideo.volume = Math.max(0, vol);
   if(volumeMouse) {
      mouseTimer = setTimeout(volume_down, 50);
   }
}
 
function setVolume() {
   if(volumeMouse) {
      myVideo.volume = volumeBar.value / 100;
   } 
}
 
//change image on play/pause button
function vidplay() {
 
    if (myVideo.paused) {
     myVideo.play();
     //button.textContent = "Pause";
     playButton.innerHTML = "pause";
    } else {
     myVideo.pause();
     //button.textContent = "Play";
     playButton.innerHTML = "play";
    }
}
 
function vidmute() {
    if (myVideo.muted) {
     myVideo.muted = false;
     //button.textContent = "Mute";
     muteButton.innerHTML = "mute";
    } else {
     myVideo.muted=true;
     //button.textContent = "Un-Mute";
     muteButton.innerHTML = "unmute";
    }
  }
  
  function getVideoWidthHeight(){
		var videotag = document.getElementById("mdabdvid");
		widthform= document.getElementById("videowidthform");
		heightform  =  document.getElementById("videoheightform");
		var sourcetag = document.getElementById("videosource");

		
		heightform.value =sourcetag.src.width ;//div
		widthform.value =sourcetag.src.height  ;//div
  }
