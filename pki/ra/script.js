function script_Question(url, msg) {
  if (confirm(msg)) document.location = url;
  else return;
}

