function str2int(date) {
    date = date.substring(0,19);
    date = date.replace(/-/g,'/'); //必须把日期'-'转为'/'
    return new Date(date).getTime();
}


function search(goods, str, tag) {
    let res = []
    for (let i = 0; i < goods.length; ++i) {
        res[i] = false
        for (let j = 0; j < goods[i].tag.length; ++j) {
            if (tag.indexOf(goods[i].tag[j]) !== -1) {
                res[i] = true
                break
            }
        }
        if (str.length > 0 && decodeURIComponent(goods[i].name).indexOf(str) !== -1)
            res[i] = true
    }
    return res
}

export {
    str2int,
    search
}