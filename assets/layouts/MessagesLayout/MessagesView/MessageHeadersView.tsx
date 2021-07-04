import React from 'react';
import {DataGrid, GridColumns} from '@material-ui/data-grid';
import {
  Message,
} from '../../../graphQL/generated/graphqlRequest';

interface Props {
  message: Message
}

const columns: GridColumns = [
  {field: 'key', headerName: 'Header',width: 200},
  {field: 'value', headerName: 'Value',flex: 1},
];

const MessageHeaders = ({message}: Props) => (
  <DataGrid
    autoHeight
    rowsPerPageOptions={[]}
    rows={message.headers.map(
      (header, index) => ({
        id: index,
        key: header.key,
        value: header.value
      })
    )}
    columns={columns}
  />
);

export default MessageHeaders;
