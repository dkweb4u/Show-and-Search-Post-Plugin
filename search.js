

if(document.querySelector(".dk-cate-search")){
    let searchUrl = document.querySelector(".dk-cate-search .roolUrl");
    let searchtext = document.querySelector(".dk-cate-search .searchtext");
    let results = document.querySelector(".dk-cate-search .results");
    let limit = document.querySelector(".dk-cate-search .limit");

    searchtext.addEventListener('input',(e)=>{
    
    if( e.target.value != ''){
        fetch(searchUrl.value + e.target.value + "&per_page=" + limit.value).then(res => res.json()).then(data => displaySearchResult(data));
    }
    else{
        results.innerHTML = '';
    }

    })

    function displaySearchResult(datas= []){
        let resultHtml = `<ul>`;

        if(datas.length != 0){          

             datas.forEach(data => {
                resultHtml += `<li><a href="${data.link}">${data.title.rendered}</a></li>`
              });
        }

        else{
             resultHtml += `<span>No Post Match</span>`;
          }

        resultHtml += `</ul>`;

        results.innerHTML = resultHtml;

       

    }

}