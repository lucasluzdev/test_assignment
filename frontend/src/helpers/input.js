export function debounce(func, delay) {
  let timerId;

  return function () {
    clearTimeout(timerId);

    timerId = setTimeout(() => {
      func.apply(this, arguments);
    }, delay);
  };
}

export function isValidEmail(email) {
  const pattern =
    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return pattern.test(email);
}

export function isValidName(name) {
  const pattern = /^[^\d]+$/u;
  return pattern.test(name);
}

export function validateData({
  id,
  first_name,
  last_name,
  email,
  confirm_email,
  status,
}) {
  const errors = [];
  let error = true;

  const dataToValidate = {
    id,
    first_name: first_name?.trim(),
    last_name: last_name?.trim(),
    email: email?.trim(),
    confirm_email: confirm_email?.trim(),
    status,
  };

  if (
    dataToValidate.first_name.length < 3 ||
    !isValidName(dataToValidate.first_name)
  ) {
    errors.push("Invalid first name! Please, check it!");
  } if (
    dataToValidate.last_name.length < 3 ||
    !isValidName(dataToValidate.last_name)
  ) {
    errors.push("Invalid last name! Please, check it!");
  } if (!isValidEmail(dataToValidate.email)) {
    errors.push("Invalid email! Please, check it!");
  } if (dataToValidate.email !== dataToValidate.confirm_email) {
    errors.push("Emails provided are not equal! Please, check it!");
  } if (dataToValidate.status < 1 || dataToValidate.status > 2) {
    errors.push("Invalid status! Please, check it");
  } if (errors.length === 0) {
    error = false;
  }
  return {
    error,
    errors,
    data: {
      ...dataToValidate,
    },
  };
}
