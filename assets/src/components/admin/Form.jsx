import { useState, useEffect } from "react";
import { useNavigate, useParams } from "react-router";
import { useForm } from "react-hook-form";
import toast, { Toaster } from "react-hot-toast";
import FormHeader from "../form/FormHeader.jsx";
import FormInputs from "../form/FormInputs.jsx";
import FormSelect from "../form/FormSelect.jsx";
import PopupToast from "../popup/PopupToast.jsx";
import ConfirmationDialog from "../ConfirmationDialog.jsx";
import PopupModal from "../popup/PopupModal.jsx";
import {
  Card,
  CardBody,
  Button
} from "@material-tailwind/react";

const Form = () => {
  const {
    register,
    handleSubmit,
    reset,
    formState: { errors },
    setValue,
    getValues,
  } = useForm();
  const navigate = useNavigate();
  const [loading, setLoading] = useState(false);
  const [open, setOpen] = useState(false);
  const [openModal, setOpenModal] = useState(false);  
  const [availableOptions, setAvailableOptions] = useState([]);
  const [selected, setSelected] = useState([]);
  const { id } = useParams();
  const toastStyle = {
    position: 'top-right',
    style: {position: 'relative', top: '80px'}
  }

  useEffect(() => {
    fetchData();
  }, []);
  const fetchData = async () => {
    await getOptions();
    if (id) {
      fetch(`${globalScript.api_url}/${id}?nonce=${globalScript.nonce}`, {
        method: 'GET',
        credentials: 'include'
      })
      .then(response => response.json())
      .then(({data}) => {
        setValue('popup_title', data.post_title);
        setValue('popup_name', data.post_name);
        setValue('popup_desc', data.post_content);
        setValue('popup_type', data.post_mime_type);
        if (data?.page.length > 0) {
          setAvailableOptions((prevOptions) => {
            return prevOptions.filter((opt) => !data.page.some((page) => page.ID === opt.value));
          });
          var x = [];
          for (let obj of data.page) {
            const y = { label: obj.post_name, value: obj.ID };
            x.push(y);
          }
          setSelected(x);
          setValue("popup_pages", x);
        }
      })
      .catch(error => console.error('Error:', error));
    }
  };

  const getOptions = async () => {
    fetch(`${globalScript.admin_url}?action=option_select_page&nonce=${globalScript.nonce}`, {
      method: 'GET'
    })
    .then(response => response.json())
    .then((data) => {
      setAvailableOptions(data);
    })
    .catch(error => console.error('Error:', error));
  };

  const handleSelectChange = (value) => {
    const selectedOption = availableOptions.find((opt) => opt.value === value);
    if (selectedOption) {
      const newValues = [...selected, selectedOption];
      setSelected(newValues);
      setAvailableOptions(availableOptions.filter((opt) => opt.value !== value));
      setValue("popup_pages", newValues);
    }
  };
  const handleRemove = (value) => {
    const removedOption = selected.find((opt) => opt.value === value);
    if (removedOption) {
      setAvailableOptions([...availableOptions, removedOption]);
      setSelected(selected.filter((opt) => opt.value !== value));
      setValue("popup_pages", selected.filter((opt) => opt.value !== value));
    }
  };

  const handleOpen = () => setOpen(!open);
  const handleConfirm = () => {
    setOpen(false);
    navigate("/");
  };

  const handlePreview = () => {
    const formData = getValues();    
    if (formData?.popup_type === "1") {
      toast.custom((t) => <PopupToast t={t} data={formData}/>, {position: "top-right"});
    } else if (formData?.popup_type === "2") {
      setOpenModal(true);
    }
  };
  
  const onSubmit = async (data) => {
    setLoading(true);
    try {
      const url = (id) ? globalScript.api_url + '/' + id : globalScript.api_url;
      const method = (id) ? 'PUT' : 'POST';
      const msg = (id) ? 'updated' : 'created';
      const response = await fetch(`${url}?nonce=${globalScript.nonce}`, {
        method: method,
        headers: { "Content-Type": "application/json" },
        credentials: 'include',
        body: JSON.stringify(data),
      });
      if (response.ok) {
        toast.success("Popup " + msg + " successfully!", toastStyle);
      } else {
        toast.error("Error " + msg + " popup!", toastStyle);
      }
    } catch (error) {
      console.error("Error " + msg + " popup", error);
      toast.error("Error " + msg + " popup!", toastStyle);
    } finally {
      setLoading(false);
      if (!id) {
        reset();
      }
    }
  };

  return (
    <div className="flex items-center justify-center w-full">
      <div className="p-6 justify-center w-full max-w-9/10">
        <Card>
          <CardBody className="flex flex-col gap-4">
          <FormHeader />
          <div className="card-body flex flex-col gap-4">
            <div className="flex items-center justify-end mb-2">
              <Button type="button" color="blue" onClick={() => handlePreview()}>
                Preview
              </Button>
              <Toaster />          
              <PopupModal openModal={openModal} setOpenModal={setOpenModal} data={getValues()}/>
            </div>
            <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
              <FormInputs register={register} errors={errors} />
              <FormSelect 
                register={register} 
                setValue={setValue}
                getValues={getValues}
                errors={errors} 
                availableOptions={availableOptions} 
                selected={selected}
                handleSelectChange={handleSelectChange}
                handleRemove={handleRemove}
              />
              <div className="flex items-center justify-end gap-4 mt-4">
                <Button type="submit" variant="gradient" loading={loading}>
                  Simpan
                </Button>
                <Button variant="outlined" onClick={handleOpen}>
                  Batal
                </Button>
                <ConfirmationDialog 
                  open={open} 
                  handleOpen={handleOpen} 
                  handleConfirm={handleConfirm}
                  field={{
                    header: 'Konfirmasi Pembatalan',
                    body: 'Apakah Anda yakin ingin membatalkan?'
                  }}
                />
              </div>
            </form>
          </div>
          </CardBody>
        </Card>
      </div>      
    </div>
  );
};

export default Form;