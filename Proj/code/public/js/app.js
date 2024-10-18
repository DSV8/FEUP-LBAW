function encodeForAjax(data) {
  if (data == null) return null;
  return Object.keys(data).map(function (k) {
    return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
  }).join('&');
}

function sendAjaxRequest(method, url, data, handler) {
  let request = new XMLHttpRequest();

  request.open(method, url, true);
  request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  if (handler) {
    request.addEventListener('load', function () {
      if (request.status === 200) {
        var response = JSON.parse(request.responseText);
        handler(response);
      } else {
        console.error('Request failed with status:', request.status);
      }

    });

  }
  request.send(encodeForAjax(data));
}

const pusher = new Pusher(pusherAppKey, {
  cluster: pusherCluster,
  encrypted: true
});

const channel = pusher.subscribe('lbaw2374');
const channelname1 = userId + '-notification';
channel.bind(channelname1, function (data) {

  const notification = document.getElementById('event');
  const notificationText = document.getElementById('eventText');
  notificationText.textContent = data.message;
  notification.classList.add('show');

  setTimeout(function () {
    notification.classList.remove('show');
  }, 500);
});

const channelname2 = userId + '-newnotification';

channel.bind(channelname2, function (data) {
  console.log("hello");
  notificationsRefresh();
});

function notificationsRefresh() {
  const notificationsBtn = document.getElementById('notifications-btn');
  const notificationsContainer = document.getElementById('notificationsContainer');
  if (notificationsBtn.className === "clicked") {
    sendAjaxRequest('post', '../unreadnotifications', { read: "true" }, function (response) {
      handleNotifications(response); // Call function to handle the notifications
    });
  }
}

function notifications() {
  const notificationsBtn = document.getElementById('notifications-btn');
  const notificationsContainer = document.getElementById('notificationsContainer');
  if (notificationsBtn.className === "not-clicked") {
    sendAjaxRequest('post', '../unreadnotifications', { read: "true" }, function (response) {
      handleNotifications(response); // Call function to handle the notifications
    });
    notificationsBtn.className = "clicked";
  }
  else {
    notificationsContainer.innerHTML = '';
    notificationsBtn.className = "not-clicked";
  }
}

function handleNotifications(response) {
  const notificationsContainer = document.getElementById('notificationsContainer'); // Get the container element

  response.forEach(function (notification) {
    // Create a div element for the notification item
    const notificationItem = document.createElement('div');
    notificationItem.classList.add('notification-item');

    // Create the profile picture element
    const profilePicture = document.createElement('div');
    profilePicture.classList.add('profile-picture');

    // Create the image element for the profile picture
    const img = document.createElement('img');
    sendAjaxRequest('post', '../profileimage', {
      type: 'profile',
      userId: notification.data.sender_id,
    }, function (response) {
      img.src = response;
      img.style.width = '50px';
      img.style.height = '50px';
    });
    img.alt = 'Profile Picture';

    // Append the profile picture to the profile picture container
    profilePicture.appendChild(img);

    // Create the notification details element
    const notificationDetails = document.createElement('div');
    notificationDetails.classList.add('notification-details');
    notificationDetails.innerHTML = `
      <p>${notification.data.name} ${notification.data.description} ${notification.data.title}</p>
    `;

    // Append profile picture and notification details to the notification item
    notificationItem.appendChild(profilePicture);
    notificationItem.appendChild(notificationDetails);

    // Append the notification item to the notifications container
    notificationsContainer.appendChild(notificationItem);
  });
}
