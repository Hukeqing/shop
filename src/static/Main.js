function str2int(date) {
    date = date.substring(0,19);
    date = date.replace(/-/g,'/'); //必须把日期'-'转为'/'
    return new Date(date).getTime();
}

export {
    str2int
}