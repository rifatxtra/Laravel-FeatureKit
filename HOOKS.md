# Hooks Usage Guide

## Available Hooks

### Authentication Hooks

#### `useAuth()`
Get complete auth object with user data and authentication status.

```jsx
import { useAuth } from '@/hooks/useSharedProps';

const auth = useAuth();
// { user: {...}, check: true }
```

#### `useUser()`
Get authenticated user directly.

```jsx
import { useUser } from '@/hooks/useSharedProps';

const user = useUser();
// { id: 1, name: 'John', email: 'john@example.com' }
```

#### `useIsAuthenticated()`
Check if user is logged in (returns boolean).

```jsx
import { useIsAuthenticated } from '@/hooks/useSharedProps';

const isAuthenticated = useIsAuthenticated();

if (isAuthenticated) {
    // User is logged in
}
```

#### `useIsGuest()`
Check if user is a guest (not logged in).

```jsx
import { useIsGuest } from '@/hooks/useSharedProps';

const isGuest = useIsGuest();

{isGuest && <LoginButton />}
```

### Role & Permission Hooks

#### `useHasRole(role)`
Check if user has a specific role (or any of multiple roles).

```jsx
import { useHasRole } from '@/hooks/useSharedProps';

const isAdmin = useHasRole('admin');
const isModerator = useHasRole(['admin', 'moderator']); // Any of these

{isAdmin && <AdminPanel />}
```

#### `useHasPermission(permission)`
Check if user has a specific permission.

```jsx
import { useHasPermission } from '@/hooks/useSharedProps';

const canEdit = useHasPermission('posts.edit');
const canManage = useHasPermission(['posts.edit', 'posts.delete']);

{canEdit && <EditButton />}
```

**Note:** For roles/permissions to work, update `HandleInertiaRequests.php`:

```php
'auth' => [
    'user' => $request->user() ? [
        'id' => $request->user()->id,
        'name' => $request->user()->name,
        'email' => $request->user()->email,
        'roles' => $request->user()->roles, // Add this
        'permissions' => $request->user()->permissions, // Add this
    ] : null,
    'check' => (bool) $request->user(),
],
```

### Validation Error Hooks

#### `useErrors()`
Get all validation errors.

```jsx
import { useErrors } from '@/hooks/useSharedProps';

const errors = useErrors();
// { email: ['Email is required'], password: ['Password must be 8 chars'] }
```

#### `useError(field)`
Get error for a specific field.

```jsx
import { useError } from '@/hooks/useSharedProps';

const emailError = useError('email');

<input type="email" />
{emailError && <span className="text-red-500">{emailError}</span>}
```

#### `useHasErrors()`
Check if any validation errors exist.

```jsx
import { useHasErrors } from '@/hooks/useSharedProps';

const hasErrors = useHasErrors();

{hasErrors && <div className="alert">Please fix the errors</div>}
```

### Flash Message Hooks

#### `useFlash()`
Get all flash messages from session.

```jsx
import { useFlash } from '@/hooks/useSharedProps';

const flash = useFlash();

{flash.success && <div className="success">{flash.success}</div>}
{flash.error && <div className="error">{flash.error}</div>}
{flash.warning && <div className="warning">{flash.warning}</div>}
{flash.info && <div className="info">{flash.info}</div>}
```

### App Configuration Hooks

#### `useAppConfig()`
Get application configuration.

```jsx
import { useAppConfig } from '@/hooks/useSharedProps';

const app = useAppConfig();
// { name: 'MyApp', url: 'http://localhost', locale: 'en' }

<footer>&copy; {app.name}</footer>
```

#### `useCsrfToken()`
Get CSRF token for forms.

```jsx
import { useCsrfToken } from '@/hooks/useSharedProps';

const csrfToken = useCsrfToken();

<input type="hidden" name="_token" value={csrfToken} />
```

## Complete Example

```jsx
import { Head } from '@inertiajs/react';
import { 
    useUser, 
    useIsAuthenticated, 
    useHasRole,
    useFlash, 
    useError 
} from '@/hooks/useSharedProps';

export default function Dashboard() {
    const user = useUser();
    const isAuth = useIsAuthenticated();
    const isAdmin = useHasRole('admin');
    const flash = useFlash();
    const emailError = useError('email');

    return (
        <>
            <Head title="Dashboard" />
            
            {flash.success && <div className="alert-success">{flash.success}</div>}
            
            {isAuth ? (
                <>
                    <h1>Welcome, {user.name}!</h1>
                    {isAdmin && <AdminPanel />}
                </>
            ) : (
                <p>Please login</p>
            )}
        </>
    );
}
```
