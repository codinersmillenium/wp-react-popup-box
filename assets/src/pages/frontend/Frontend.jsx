import { useEffect, useState } from 'react';
import PopupModal from '../../components/popup/PopupModal.jsx';
import PopupToast from '../../components/popup/PopupToast.jsx';
import ReactDOM from "react-dom/client";
import { toast, Toaster } from 'react-hot-toast';

const Frontend = () => {
  const [data, setPopup] = useState(globalScript.data_popup);
  const [openModal, setOpenModal] = useState(false);

  useEffect(() => {
    handlePreview()
  }, [])
  const handlePreview = () => {
    if (data?.popup_type === "1") {
      toast.custom((t) => <PopupToast t={t} data={data}/>, {position: "top-right"});
    } else if (data?.popup_type === "2") {
      setOpenModal(true);
    }
  };
  return (
    <div>
      {data && (
        <PopupModal openModal={openModal} setOpenModal={setOpenModal} data={data} />
      )}
      <Toaster />
    </div>
  );
};

const container = document.getElementById("root");
if (container) {
  const root = ReactDOM.createRoot(container);
  root.render(<Frontend/>);
}
