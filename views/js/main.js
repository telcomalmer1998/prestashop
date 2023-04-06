window.onload = function(){
    // console.log(window.location.pathname)
    if((window.location.pathname.includes('/order')) || (window.location.pathname.includes('/ar'))){
        let label = document.querySelectorAll('span')
            for( let i = 0 ; i < label.length ; i++){
                // console.log(label[i].innerText)
                if(label[i].innerText.includes(' ادفعها كاملة ') || label[i].innerText.includes('Pay in full') ){
                    swapElements(label[i], label[i].nextElementSibling)
                }
                if(label[i].innerText.includes('قسم فاتورتك على') || label[i].innerText.includes('Split') ){
                    swapElements(label[i], label[i].nextElementSibling)
                }
                if(label[i].innerText.includes('ادفع الشهر الجاي') || label[i].innerText.includes('Pay next month') ){
                    swapElements(label[i], label[i].nextElementSibling)
                }
            }

            function swapElements(obj1, obj2) {
                // create marker element and insert it where obj1 is
                var temp = document.createElement("div");
                obj1.parentNode.insertBefore(temp, obj1);
            
                // move obj1 to right before obj2
                obj2.parentNode.insertBefore(obj1, obj2);
            
                // move obj2 to right before where obj1 used to be
                temp.parentNode.insertBefore(obj2, temp);
            
                // remove temporary marker node
                temp.parentNode.removeChild(temp);
            } 

        }      
};
