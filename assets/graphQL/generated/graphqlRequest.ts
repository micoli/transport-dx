import { GraphQLClient } from 'graphql-request';
import * as Dom from 'graphql-request/dist/types.dom';
import gql from 'graphql-tag';
export type Maybe<T> = T | null;
export type Exact<T extends { [key: string]: unknown }> = { [K in keyof T]: T[K] };
export type MakeOptional<T, K extends keyof T> = Omit<T, K> & { [SubKey in K]?: Maybe<T[SubKey]> };
export type MakeMaybe<T, K extends keyof T> = Omit<T, K> & { [SubKey in K]: Maybe<T[SubKey]> };
/** All built-in and custom scalars, mapped to their actual values */
export type Scalars = {
  ID: string;
  String: string;
  Boolean: boolean;
  Int: number;
  Float: number;
};

export type Address = {
  __typename?: 'Address';
  address?: Maybe<Scalars['String']>;
  display?: Maybe<Scalars['String']>;
};

export type Attachment = {
  __typename?: 'Attachment';
  contentId?: Maybe<Scalars['String']>;
  contentType?: Maybe<Scalars['String']>;
  contentDisposition?: Maybe<Scalars['String']>;
  filename?: Maybe<Scalars['String']>;
  content?: Maybe<Scalars['String']>;
};

export type Group = {
  __typename?: 'Group';
  name?: Maybe<Scalars['String']>;
  numberOfMessage?: Maybe<Scalars['Int']>;
};

export type Message = {
  __typename?: 'Message';
  id?: Maybe<Scalars['String']>;
  date?: Maybe<Scalars['String']>;
  subject?: Maybe<Scalars['String']>;
  from?: Maybe<Address>;
  recipients?: Maybe<Array<Maybe<Address>>>;
  attachments?: Maybe<Array<Maybe<Attachment>>>;
  body?: Maybe<Scalars['String']>;
};

export type Query = {
  __typename?: 'Query';
  messages?: Maybe<Array<Maybe<Message>>>;
  message?: Maybe<Message>;
  groups?: Maybe<Array<Maybe<Group>>>;
};


export type QueryMessageArgs = {
  messageId: Scalars['String'];
};

export type GroupsQueryVariables = Exact<{ [key: string]: never; }>;


export type GroupsQuery = (
  { __typename?: 'Query' }
  & { groups?: Maybe<Array<Maybe<(
    { __typename?: 'Group' }
    & Pick<Group, 'name' | 'numberOfMessage'>
  )>>> }
);

export type MessageQueryVariables = Exact<{
  messageId: Scalars['String'];
}>;


export type MessageQuery = (
  { __typename?: 'Query' }
  & { message?: Maybe<(
    { __typename?: 'Message' }
    & Pick<Message, 'id' | 'date' | 'subject' | 'body'>
    & { from?: Maybe<(
      { __typename?: 'Address' }
      & Pick<Address, 'address'>
    )>, recipients?: Maybe<Array<Maybe<(
      { __typename?: 'Address' }
      & Pick<Address, 'address' | 'display'>
    )>>>, attachments?: Maybe<Array<Maybe<(
      { __typename?: 'Attachment' }
      & Pick<Attachment, 'contentId' | 'contentType' | 'contentDisposition'>
    )>>> }
  )> }
);

export type MessagesQueryVariables = Exact<{ [key: string]: never; }>;


export type MessagesQuery = (
  { __typename?: 'Query' }
  & { messages?: Maybe<Array<Maybe<(
    { __typename?: 'Message' }
    & Pick<Message, 'date' | 'id' | 'subject'>
    & { from?: Maybe<(
      { __typename?: 'Address' }
      & Pick<Address, 'address'>
    )> }
  )>>> }
);


export const GroupsDocument = gql`
    query groups {
  groups {
    name
    numberOfMessage
  }
}
    `;
export const MessageDocument = gql`
    query message($messageId: String!) {
  message(messageId: $messageId) {
    id
    date
    subject
    from {
      address
    }
    recipients {
      address
      display
    }
    body
    attachments {
      contentId
      contentType
      contentDisposition
    }
  }
}
    `;
export const MessagesDocument = gql`
    query messages {
  messages {
    date
    id
    subject
    from {
      address
    }
  }
}
    `;

export type SdkFunctionWrapper = <T>(action: (requestHeaders?:Record<string, string>) => Promise<T>, operationName: string) => Promise<T>;


const defaultWrapper: SdkFunctionWrapper = (action, _operationName) => action();

export function getSdk(client: GraphQLClient, withWrapper: SdkFunctionWrapper = defaultWrapper) {
  return {
    groups(variables?: GroupsQueryVariables, requestHeaders?: Dom.RequestInit["headers"]): Promise<GroupsQuery> {
      return withWrapper((wrappedRequestHeaders) => client.request<GroupsQuery>(GroupsDocument, variables, {...requestHeaders, ...wrappedRequestHeaders}), 'groups');
    },
    message(variables: MessageQueryVariables, requestHeaders?: Dom.RequestInit["headers"]): Promise<MessageQuery> {
      return withWrapper((wrappedRequestHeaders) => client.request<MessageQuery>(MessageDocument, variables, {...requestHeaders, ...wrappedRequestHeaders}), 'message');
    },
    messages(variables?: MessagesQueryVariables, requestHeaders?: Dom.RequestInit["headers"]): Promise<MessagesQuery> {
      return withWrapper((wrappedRequestHeaders) => client.request<MessagesQuery>(MessagesDocument, variables, {...requestHeaders, ...wrappedRequestHeaders}), 'messages');
    }
  };
}
export type Sdk = ReturnType<typeof getSdk>;