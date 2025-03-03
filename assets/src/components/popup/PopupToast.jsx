import { XCircleIcon, BellAlertIcon } from "@heroicons/react/24/solid";
import classNames from "classnames";

const PopupToast = ({t, data}) => {
  return (
    <div className={classNames(["notification-wrapper", t.visible ? "top-20" : "-top-96"])}>
      <div className="icon-wrapper">
        <BellAlertIcon color="white" className="h-6 w-6" />
      </div>
      <div className="content-wrapper">
        <h1>{data.popup_title}</h1>
        <p>{data.popup_desc}</p>
      </div>
      <div className="close-icon" onClick={() => toast.dismiss(t.id)}>
        <XCircleIcon color="white" className="h-6 w-6" />
      </div>
    </div>
  );
};

export default PopupToast;