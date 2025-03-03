import React, { useState, useEffect } from "react";
import { Link } from "react-router";
import { MagnifyingGlassIcon, PencilIcon, TrashIcon } from "@heroicons/react/24/outline";
import { KeyIcon, UserPlusIcon } from "@heroicons/react/24/solid";
import {
  Card,
  CardHeader,
  Input,
  Typography,
  Button,
  CardBody,  
  IconButton,
  Tooltip,
  Checkbox
} from "@material-tailwind/react";
import ConfirmationDialog from "../ConfirmationDialog.jsx";
import toast, { Toaster } from "react-hot-toast";

const TABLE_HEAD = ["", "Nama Popup", "Judul Popup", "Deskripsi", "Halaman", "Aksi"];
const toastStyle = {
  position: 'top-right',
  style: {position: 'relative', top: '80px'}
}

export function Table () {
  const [data, setData] = useState([]);
  const [search, setSearch] = useState("");
  const [deleteId, setDeleteId] = useState(null);

  useEffect(() => {
    fetchData();    
  }, []);

  const fetchData = async () => {
    await fetch(`${globalScript.api_url}?nonce=${globalScript.nonce}`, {
      method: 'GET',
      credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
      const result = data?.data || [];
      setData(result);
    })
    .catch(error => console.error('Error:', error));
  };
 
  const handleCheckboxChange = async (event, id) => {
    const data = {enable: event.target.checked ? 1 : 0}
    await fetch(`${globalScript.popup_enable}/${id}?nonce=${globalScript.nonce}`, {
      method: 'PUT',
      headers: { "Content-Type": "application/json" },
      credentials: 'include',
      body: JSON.stringify(data)
    })
    .then((resp) => {
      if (resp.ok) {
        toast.success("Success enable/disable popup!", toastStyle);
      } else {
        toast.error("Error enable/disable popup!", toastStyle);
      }
    })
    .catch (() => {
      toast.error("Error enable/disable popup!", toastStyle);
    });
  };

  const getToken = async () => {
    fetch(`${globalScript.admin_url}?action=get_token&nonce=${globalScript.nonce}`, {
      method: 'GET'
    })
    .then(response => response.json())
    .then((data) => {
      alert(data);
    })
    .catch(error => console.error('Error:', error));
  };

  const handleDeleteConfirm = (id) => { 
    setDeleteId(id);
  };

  const handleDelete = async () => {
    await setDeleteId(null);
    fetch(`${globalScript.api_url}/${deleteId}?nonce=${globalScript.nonce}`, {
      method: 'DELETE',
      credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
      if (data?.success) {
        toast.success("Popup deleted successfully!", toastStyle);
        fetchData();
      } else {
        toast.error("Error deleting popup!", toastStyle);
      }
    })
    .catch(() => { toast.error("Error deleting popup!", toastStyle); });
  };

  const handleSearch = (e) => {
    setSearch(e.target.value);
  };

  const filteredData = (data.length > 0) ? data.filter((item) => 
    item.post_title.toLowerCase().includes(search.toLowerCase())
  ) : [];

  return (
    <Card className="m-8">
      <CardHeader floated={false} shadow={false} className="rounded-none">
        <div className="mb-8 flex items-center justify-between">
          <Typography variant="h5" color="blue-gray">
            Popup Box List 
          </Typography>
          <div className="flex items-center gap-2">
            <Link to="/create">
              <Button className="flex items-center gap-3" size="sm">
                <UserPlusIcon className="h-4 w-4" /> Add Popup Box
              </Button>
            </Link>
            <Tooltip content="Token auth for development">
              <Button className="flex items-center gap-3" size="sm" onClick={() => getToken()}>
                <KeyIcon className="h-4 w-4" /> Get Token
              </Button>
            </Tooltip>
          </div>
        </div> 
        <div className="flex justify-between">
          <Input label="Search" icon={<MagnifyingGlassIcon className="h-5 w-5" />} value={search} onChange={handleSearch} />
        </div>
        <Toaster />
      </CardHeader>
      <CardBody className="overflow-auto">
        <table className="w-full min-w-max table-auto text-left">
          <thead>
            <tr>
              {TABLE_HEAD.map((head) => (
                <th key={head} className="border-b p-4">{head}</th>
              ))}
            </tr>
          </thead>
          <tbody>
            {(filteredData.length > 0) ? (
              filteredData.map((item) => (
              <tr key={item.ID}>                
                <td className="p-4">
                  <Tooltip content="Enable/Disable Popup">
                    <Checkbox color="blue" defaultChecked={item.post_status === 'publish' ? true : false} 
                      onChange={(event) => handleCheckboxChange(event, item.ID)} 
                    />
                  </Tooltip>
                </td>
                <td className="p-4">{item.post_name}</td>
                <td className="p-4">{item.post_title}</td>
                <td className="p-4">{item.post_content}</td>
                <td className="p-4">
                  {item.page.map((item_page, i) => {
                    const name = item_page.post_name;                    
                    return name + (i < item.page.length - 1 ? ', ' : '');
                  })}
                </td>
                <td className="p-4 flex gap-2">
                  <Tooltip content="Edit">
                    <Link to={"/update/" + item.ID}>
                      <IconButton variant="text">
                        <PencilIcon className="h-4 w-4" />
                      </IconButton>
                    </Link>
                  </Tooltip>
                  <Tooltip content="Delete">
                    <IconButton variant="text" color="red" onClick={() => handleDeleteConfirm(item.ID)}>
                      <TrashIcon className="h-4 w-4" />
                    </IconButton>
                  </Tooltip>
                </td>
              </tr>
            ))) : (
              <tr>
                <td colSpan="5" className="p-4 text-center">Data tidak ditemukan....</td>
              </tr>
            )}
          </tbody>
        </table>
      </CardBody>

      {deleteId && (
        <ConfirmationDialog 
          open={!!deleteId} 
          handleOpen={() => setDeleteId(null)} 
          handleConfirm={handleDelete}
          field={{
            header: 'Konfirmasi Hapus Data',
            body: 'Apakah Anda yakin ingin menghapus?'
          }}
        />
      )}
    </Card>
  );
}