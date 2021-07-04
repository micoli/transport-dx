import { GraphQLClient } from 'graphql-request';

const query = (queryPayload: string, headers: Map<string, string>) => fetch('/graphql/', {
  method: 'post',
  // @ts-ignore
  headers: {
    Accept: 'application/json, text/plain, */*',
    'Content-Type': 'application/json',
    ...headers
  },
  body: JSON.stringify({ query: queryPayload })
});

const mutation = (mutationPayload: string, headers: Map<string, string>, files: any[] = []) => {
  if (files.length === 0) {
    return fetch('/graphql/', {
      method: 'post',
      // @ts-ignore
      headers: {
        Accept: 'application/json, text/plain, */*',
        'Content-Type': 'application/json',
        ...headers
      },
      body: JSON.stringify({ query: mutationPayload })
    });
  }

  const data = new FormData();
  data.append('query', mutationPayload);
  files.forEach((file) => data.append('files[]', file));

  return fetch('/graphql/', {
    method: 'post',
    // @ts-ignore
    headers: {
      Accept: 'application/json, text/plain, */*',
      ...headers
    },
    body: data
  });
};

const graphQLClient = new GraphQLClient(
  '/graphql/', {
    headers: {},
  }
);

export {
  query,
  mutation,
  graphQLClient
};
