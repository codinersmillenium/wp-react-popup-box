import { Dialog, DialogHeader, DialogBody, DialogFooter, Button, Typography } from "@material-tailwind/react";

const PopupModal = ({ openModal, setOpenModal, data }) => {
  return (
    <Dialog open={openModal} handler={() => setOpenModal(false)}>
      <DialogHeader>{data.popup_title}</DialogHeader>
      <DialogBody className="overflow-scroll">
        <Typography className="font-normal">
          {data.popup_desc}
        </Typography>
      </DialogBody>
      <DialogFooter>
        <Button onClick={() => setOpenModal(false)} color="red">
          TUTUP
        </Button>
      </DialogFooter>
    </Dialog>
  );
};

export default PopupModal;
