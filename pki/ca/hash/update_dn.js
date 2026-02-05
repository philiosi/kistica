function update_dn() {
  var form = document.updateform;
  var cn = form.cn.value;
  form.dn.value = "/CN=" + cn;
}
