import React from 'react';
import {DataGrid, GridColumns} from '@material-ui/data-grid';
import {
  Message,
} from '../../../graphQL/generated/graphqlRequest';

interface Props {
  message: Message
}

const columns: GridColumns = [
  {field: 'id', headerName: 'Header', width: 200},
  {field: 'filename', headerName: 'Filename', flex: 1},
  {field: 'contentType', headerName: 'Type', flex: 1},
  {field: 'contentDisposition', headerName: 'Type', flex: 1},
];

const MessageAttachments = ({message}: Props) => (
  <DataGrid
    autoHeight
    rowsPerPageOptions={[]}
    rows={message.attachments.map(
      (attachment) => ({
        id: attachment.contentId,
        filename: attachment.filename,
        contentType: attachment.contentType,
        contentDisposition: attachment.contentDisposition,
      })
    )}
    columns={columns}
  />
);

export default MessageAttachments;
