const toasteur = new Toasteur("top-right", 7000);
Toultip.init();

$$(".sideBarTitle").forEach(element => {
  element.classList.add("section")
  element.classList.add("box")
  element.classList.add("glowing")
})

document.body.ondragover = function(e) {
  e.preventDefault()
  return false;
}
document.body.ondrop = function(event) {
  event.preventDefault()
  if (!event.target.classList.contains('drop'))
    if (event.dataTransfer.files.length > 0)
      toasteur.success("Very interesting file!")

};


var url = new URL(document.location.href)

if (url.searchParams.has("notificationTitle")) {

  checkNotificationCode()
  var notificationTitle = $_GET("notificationTitle");
  var notificationContent = $_GET("notificationContent");
  var notificationType = $_GET("notificationType");
  var notificationCode = $_GET("notificationCode");
  var notificationUrl = $_GET("notificationUrl")
  if (notificationCode == getNotificationCode())
    toasteur.show(notificationType, notificationContent, notificationTitle, () => { if (notificationUrl) redirect(notificationUrl) })

  var newUrl = new URL(document.location.href)

  newUrl.searchParams.delete("notificationTitle");
  newUrl.searchParams.delete("notificationContent");
  newUrl.searchParams.delete("notificationType");
  newUrl.searchParams.delete("notificationCode");
  newUrl.searchParams.delete("notificationUrl")

  history.replaceState({}, '', newUrl.href)
}
